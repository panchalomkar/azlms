<?php
require_once('../config.php');
require_login();
global $USER, $DB;

// Prepare result array
$notifications = [];

// --- 1️⃣ Course enrolment notifications ---
$enrolments = $DB->get_records_sql("
    SELECT c.id AS courseid, c.fullname AS coursename, ue.timecreated
    FROM {user_enrolments} ue
    JOIN {enrol} e ON e.id = ue.enrolid
    JOIN {course} c ON c.id = e.courseid
    WHERE ue.userid = :userid
      AND ue.timecreated > :lastmonth
    ORDER BY ue.timecreated DESC
", [
    'userid'    => $USER->id,
    'lastmonth' => time() - (30 * 24 * 60 * 60)
]);

foreach ($enrolments as $enrol) {
    $notifications[] = [
        'id'      => 'enrol_' . $enrol->courseid,
        'type'    => 'enrolment',
        'message' => "You have been enrolled in course: {$enrol->coursename}",
        'time'    => userdate($enrol->timecreated),
        'url'     => (new moodle_url('/course/view.php', ['id' => $enrol->courseid]))->out(false)
    ];
}

// --- 2️⃣ Assignment notifications (submitted) ---
$assignments = $DB->get_records_sql("
    SELECT a.id AS assignid, a.name AS assignname, c.fullname AS coursename,
           s.timecreated, s.status
    FROM {assign_submission} s
    JOIN {assign} a ON a.id = s.assignment
    JOIN {course} c ON c.id = a.course
    WHERE s.userid = :userid
      AND s.timecreated > :lastmonth
    ORDER BY s.timecreated DESC
", [
    'userid'    => $USER->id,
    'lastmonth' => time() - (30 * 24 * 60 * 60)
]);

foreach ($assignments as $assign) {
    $status = ($assign->status == 1) ? 'Submitted' : 'Not submitted';
    $notifications[] = [
        'id'      => 'assign_' . $assign->assignid,
        'type'    => 'assignment',
        'message' => "Assignment '{$assign->assignname}' in course '{$assign->coursename}' status: {$status}",
        'time'    => userdate($assign->timecreated),
        'url'     => (new moodle_url('/mod/assign/view.php', ['id' => $assign->assignid]))->out(false)
    ];
}

// --- 3️⃣ Google Meet notifications ---
try {
    if ($DB->get_manager()->table_exists('gmeet')) {
        $gmeets = $DB->get_records_sql("
            SELECT g.id, g.name, g.starttime, g.joinurl, c.fullname
            FROM {gmeet} g
            JOIN {course} c ON c.id = g.courseid
            JOIN {context} ctx ON ctx.instanceid = c.id AND ctx.contextlevel = 50
            JOIN {role_assignments} ra ON ra.contextid = ctx.id
            WHERE ra.userid = :userid
              AND g.starttime > :lastmonth
            ORDER BY g.starttime DESC
        ", [
            'userid'    => $USER->id,
            'lastmonth' => time() - (30 * 24 * 60 * 60)
        ]);

        foreach ($gmeets as $gm) {
            $notifications[] = [
                'id'      => 'gmeet_' . $gm->id,
                'type'    => 'gmeet',
                'message' => "Google Meet '{$gm->name}' scheduled in course: {$gm->fullname}",
                'time'    => userdate($gm->starttime),
                'url'     => $gm->joinurl
            ];
        }
    }
} catch (Exception $e) {
    // skip gmeet if not available
}

// --- 4️⃣ Upcoming events ---
$events = $DB->get_records_sql("
    SELECT e.id, e.name, e.timestart, e.timeduration, c.fullname
    FROM {event} e
    JOIN {course} c ON c.id = e.courseid
    JOIN {context} ctx ON ctx.instanceid = c.id AND ctx.contextlevel = 50
    JOIN {role_assignments} ra ON ra.contextid = ctx.id
    WHERE ra.userid = :userid
      AND e.timestart BETWEEN :now AND :nextmonth
    ORDER BY e.timestart ASC
", [
    'userid'    => $USER->id,
    'now'       => time(),
    'nextmonth' => time() + (30 * 24 * 60 * 60)
]);

foreach ($events as $ev) {
    $start = userdate($ev->timestart, '%a, %d %b %Y, %I:%M %p');
    $end   = userdate($ev->timestart + $ev->timeduration, '%I:%M %p');
    $notifications[] = [
        'id'      => 'event_' . $ev->id,
        'type'    => 'event',
        'message' => "Upcoming event '{$ev->name}' in course '{$ev->fullname}'<br>From {$start} to {$end}",
        'time'    => $start,
        'url'     => (new moodle_url('/calendar/view.php', ['id' => $ev->id]))->out(false)
    ];
}

// --- 5️⃣ Approved Timesheet Notifications ---
if ($DB->get_manager()->table_exists('externship_timesheet')) {
    $approved = $DB->get_records_sql("
        SELECT id, userid, status, timemodified, attendhrs
        FROM {externship_timesheet}
        WHERE userid = :userid
          AND status = 'approved'
          AND timemodified > :lastmonth
        ORDER BY timemodified DESC
    ", [
        'userid'    => $USER->id,
        'lastmonth' => time() - (30 * 24 * 60 * 60)
    ]);

    foreach ($approved as $rec) {
        $notifications[] = [
            'id'      => 'timesheet_' . $rec->id,
            'type'    => 'approval',
            'message' => "Your timesheet for {$rec->attendhrs} attended hours has been approved.",
            'time'    => userdate($rec->timemodified),
            'url'     => (new moodle_url('/dashboard/result.php', ['timesheet' => $rec->id]))->out(false)
        ];
    }
}

// --- 6️⃣ NEW: Pending Assignments (due but not submitted) ---
$pending_assignments = $DB->get_records_sql("
    SELECT a.id AS assignid, a.name AS assignname, a.duedate,
           c.fullname AS coursename, cm.id AS cmid
    FROM {assign} a
    JOIN {course} c           ON c.id = a.course
    JOIN {course_modules} cm  ON cm.instance = a.id
    JOIN {modules} mo         ON mo.id = cm.module AND mo.name = 'assign'
    JOIN {enrol} e            ON e.courseid = c.id
    JOIN {user_enrolments} ue ON ue.enrolid = e.id AND ue.userid = :uid
    LEFT JOIN {assign_submission} sub
           ON sub.assignment = a.id
          AND sub.userid = :uid2
          AND sub.status = 'submitted'
    WHERE (a.duedate = 0 OR a.duedate > :now)
      AND sub.id IS NULL
      AND cm.visible = 1
      AND c.visible  = 1
    ORDER BY a.duedate ASC
    LIMIT 15
", [
    'uid'  => $USER->id,
    'uid2' => $USER->id,
    'now'  => time()
]);

foreach ($pending_assignments as $pa) {
    $due = $pa->duedate
        ? userdate($pa->duedate, '%d %b %Y, %I:%M %p')
        : 'No deadline';
    $notifications[] = [
        'id'      => 'pending_assign_' . $pa->assignid,
        'type'    => 'pending_assignment',
        'message' => "⚠️ Assignment '{$pa->assignname}' in '{$pa->coursename}' not submitted yet. Due: {$due}",
        'time'    => $due,
        'url'     => (new moodle_url('/mod/assign/view.php', ['id' => $pa->cmid]))->out(false)
    ];
}

// --- 7️⃣ NEW: Pending Quizzes (open but not attempted) ---
$pending_quizzes = $DB->get_records_sql("
    SELECT q.id AS quizid, q.name AS quizname, q.timeclose,
           c.fullname AS coursename, cm.id AS cmid
    FROM {quiz} q
    JOIN {course} c           ON c.id = q.course
    JOIN {course_modules} cm  ON cm.instance = q.id
    JOIN {modules} mo         ON mo.id = cm.module AND mo.name = 'quiz'
    JOIN {enrol} e            ON e.courseid = c.id
    JOIN {user_enrolments} ue ON ue.enrolid = e.id AND ue.userid = :uid
    LEFT JOIN {quiz_attempts} qa
           ON qa.quiz = q.id
          AND qa.userid = :uid2
          AND qa.state  = 'finished'
    WHERE (q.timeclose = 0 OR q.timeclose > :now)
      AND (q.timeopen  = 0 OR q.timeopen  < :now2)
      AND qa.id IS NULL
      AND cm.visible = 1
      AND c.visible  = 1
    ORDER BY q.timeclose ASC
    LIMIT 15
", [
    'uid'  => $USER->id,
    'uid2' => $USER->id,
    'now'  => time(),
    'now2' => time()
]);

foreach ($pending_quizzes as $pq) {
    $closes = $pq->timeclose
        ? userdate($pq->timeclose, '%d %b %Y, %I:%M %p')
        : 'No deadline';
    $notifications[] = [
        'id'      => 'pending_quiz_' . $pq->quizid,
        'type'    => 'pending_quiz',
        'message' => "📋 Quiz '{$pq->quizname}' in '{$pq->coursename}' not attempted yet. Closes: {$closes}",
        'time'    => $closes,
        'url'     => (new moodle_url('/mod/quiz/view.php', ['id' => $pq->cmid]))->out(false)
    ];
}

// --- 8️⃣ NEW: Incomplete Modules ---
$incomplete_modules = $DB->get_records_sql("
    SELECT cm.id AS cmid, m.name AS module_type, c.fullname AS coursename
    FROM {course_modules} cm
    JOIN {modules} m          ON m.id = cm.module
    JOIN {course} c           ON c.id = cm.course
    JOIN {enrol} e            ON e.courseid = c.id
    JOIN {user_enrolments} ue ON ue.enrolid = e.id AND ue.userid = :uid
    LEFT JOIN {course_modules_completion} cmc
           ON cmc.coursemoduleid = cm.id AND cmc.userid = :uid2
    WHERE cm.visible    = 1
      AND c.visible     = 1
      AND cm.completion > 0
      AND (cmc.completionstate IS NULL OR cmc.completionstate = 0)
    ORDER BY c.fullname ASC
    LIMIT 15
", [
    'uid'  => $USER->id,
    'uid2' => $USER->id
]);

foreach ($incomplete_modules as $im) {
    $cm    = get_coursemodule_from_id($im->module_type, $im->cmid);
    $title = $cm ? $cm->name : ucfirst($im->module_type) . ' activity';
    $notifications[] = [
        'id'      => 'incomplete_mod_' . $im->cmid,
        'type'    => 'incomplete_module',
        'message' => "📚 Module '{$title}' in '{$im->coursename}' is incomplete.",
        'time'    => '',
        'url'     => (new moodle_url('/mod/' . $im->module_type . '/view.php', ['id' => $im->cmid]))->out(false)
    ];
}

// --- Count unread notifications ---
$unreadcount = 0;
if ($DB->get_manager()->table_exists('user_notifications')) {
    $unreadcount = $DB->count_records('user_notifications', [
        'userid' => $USER->id,
        'isread' => 0
    ]);
}

// --- Pending items count (for badge) ---
$pending_count = count($pending_assignments)
               + count($pending_quizzes)
               + count($incomplete_modules);

// ✅ Return JSON
header('Content-Type: application/json');
echo json_encode([
    'status'        => 'success',
    'notifications' => $notifications,
    'unread_count'  => $unreadcount,
    'pending_count' => $pending_count
]);
exit;