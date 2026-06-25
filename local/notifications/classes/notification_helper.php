<?php
namespace local_notifications;
defined('MOODLE_INTERNAL') || die();

class notification_helper {

    private int $lastmonth;

    public function __construct() {
        $this->lastmonth = time() - (30 * 24 * 60 * 60);
    }

    // ── Time ago ────────────────────────────────────────────────
    private function time_ago(int $ts): string {
        if (!$ts) return '';
        $diff = time() - $ts;
        if ($diff < 60)    return 'Just now';
        if ($diff < 3600)  return (int)($diff/60)  . ' m ago';
        if ($diff < 86400) return (int)($diff/3600) . ' h ago';
        return (int)($diff/86400) . ' d ago';
    }

    // ── Master method ───────────────────────────────────────────
    public function get_all(int $userid): array {
        $notifications = [];

        $notifications = array_merge($notifications,
            $this->get_enrolments($userid),
            $this->get_assignments($userid),
            $this->get_gmeet($userid),
            $this->get_events($userid),
            $this->get_approved_timesheets($userid),
            $this->get_pending_assignments($userid),
            $this->get_pending_quizzes($userid),
            $this->get_incomplete_modules($userid)
        );

        // Sort newest first
        usort($notifications, fn($a,$b) => ($b['timestamp'] ?? 0) <=> ($a['timestamp'] ?? 0));

        $unread_count  = $this->get_unread_count($userid);
        $pending_count = count($this->get_pending_assignments($userid))
                       + count($this->get_pending_quizzes($userid));

        return [
            'notifications' => $notifications,
            'unread_count'  => $unread_count,
            'pending_count' => $pending_count,
        ];
    }

    // ── 1. Course enrolments ─────────────────────────────────────
    private function get_enrolments(int $userid): array {
        global $DB, $CFG;
        $rows = $DB->get_records_sql("
            SELECT c.id AS courseid, c.fullname, ue.timecreated
              FROM {user_enrolments} ue
              JOIN {enrol} e   ON e.id  = ue.enrolid
              JOIN {course} c  ON c.id  = e.courseid
             WHERE ue.userid = ? AND ue.timecreated > ?
             ORDER BY ue.timecreated DESC
        ", [$userid, $this->lastmonth]);

        $out = [];
        foreach ($rows as $r) {
            $out[] = [
                'id'        => 'enrol_' . $r->courseid,
                'type'      => 'enrolment',
                'message'   => "A new course \"{$r->fullname}\" has been assigned to you",
                'time'      => $this->time_ago($r->timecreated),
                'timestamp' => $r->timecreated,
                'url'       => (new \moodle_url('/course/view.php',['id'=>$r->courseid]))->out(false),
                'isread'    => false,
            ];
        }
        return $out;
    }

    // ── 2. Assignments submitted ─────────────────────────────────
    private function get_assignments(int $userid): array {
        global $DB;
        $rows = $DB->get_records_sql("
            SELECT a.id, a.name, c.fullname, s.timecreated, s.status
              FROM {assign_submission} s
              JOIN {assign} a  ON a.id  = s.assignment
              JOIN {course} c  ON c.id  = a.course
             WHERE s.userid = ? AND s.timecreated > ?
             ORDER BY s.timecreated DESC
        ", [$userid, $this->lastmonth]);

        $out = [];
        foreach ($rows as $r) {
            $out[] = [
                'id'        => 'assign_' . $r->id,
                'type'      => 'assignment',
                'message'   => "Assignment submission deadline approaching in 24 hours.",
                'time'      => $this->time_ago($r->timecreated),
                'timestamp' => $r->timecreated,
                'url'       => (new \moodle_url('/mod/assign/view.php',['id'=>$r->id]))->out(false),
                'isread'    => false,
            ];
        }
        return $out;
    }

    // ── 3. Google Meet ───────────────────────────────────────────
    private function get_gmeet(int $userid): array {
        global $DB;
        $out = [];
        try {
            if (!$DB->get_manager()->table_exists('gmeet')) return [];
            $rows = $DB->get_records_sql("
                SELECT g.id, g.name, g.starttime, g.joinurl, c.fullname
                  FROM {gmeet} g
                  JOIN {course} c  ON c.id = g.courseid
                  JOIN {context} ctx ON ctx.instanceid = c.id AND ctx.contextlevel = 50
                  JOIN {role_assignments} ra ON ra.contextid = ctx.id
                 WHERE ra.userid = ? AND g.starttime > ?
                 ORDER BY g.starttime DESC
            ", [$userid, $this->lastmonth]);

            foreach ($rows as $r) {
                $out[] = [
                    'id'        => 'gmeet_' . $r->id,
                    'type'      => 'gmeet',
                    'message'   => "Google Meet \"{$r->name}\" scheduled in {$r->fullname}",
                    'time'      => $this->time_ago($r->starttime),
                    'timestamp' => $r->starttime,
                    'url'       => $r->joinurl,
                    'isread'    => false,
                ];
            }
        } catch (\Throwable $e) { /* skip */ }
        return $out;
    }

    // ── 4. Upcoming events ───────────────────────────────────────
    private function get_events(int $userid): array {
        global $DB;
        $rows = $DB->get_records_sql("
            SELECT e.id, e.name, e.timestart, e.timeduration, c.fullname
              FROM {event} e
              JOIN {course} c   ON c.id = e.courseid
              JOIN {context} ctx ON ctx.instanceid = c.id AND ctx.contextlevel = 50
              JOIN {role_assignments} ra ON ra.contextid = ctx.id
             WHERE ra.userid = ? AND e.timestart BETWEEN ? AND ?
             ORDER BY e.timestart ASC
        ", [$userid, time(), time() + (30*24*3600)]);

        $out = [];
        foreach ($rows as $r) {
            $out[] = [
                'id'        => 'event_' . $r->id,
                'type'      => 'event',
                'message'   => "Upcoming event \"{$r->name}\" in {$r->fullname}",
                'time'      => $this->time_ago($r->timestart),
                'timestamp' => $r->timestart,
                'url'       => (new \moodle_url('/calendar/view.php',['id'=>$r->id]))->out(false),
                'isread'    => false,
            ];
        }
        return $out;
    }

    // ── 5. Approved timesheets ───────────────────────────────────
    private function get_approved_timesheets(int $userid): array {
        global $DB;
        $out = [];
        try {
            if (!$DB->get_manager()->table_exists('externship_timesheet')) return [];
            $rows = $DB->get_records_sql("
                SELECT id, attendhrs, timemodified
                  FROM {externship_timesheet}
                 WHERE userid = ? AND status = 'Approved' AND timemodified > ?
                 ORDER BY timemodified DESC
            ", [$userid, $this->lastmonth]);

            foreach ($rows as $r) {
                $out[] = [
                    'id'        => 'timesheet_' . $r->id,
                    'type'      => 'approval',
                    'message'   => "Your timesheet for {$r->attendhrs} hrs has been approved.",
                    'time'      => $this->time_ago($r->timemodified),
                    'timestamp' => $r->timemodified,
                    'url'       => (new \moodle_url('/local/result/index.php'))->out(false),
                    'isread'    => false,
                ];
            }
        } catch (\Throwable $e) { /* skip */ }
        return $out;
    }

    // ── 6. Pending assignments ───────────────────────────────────
    private function get_pending_assignments(int $userid): array {
        global $DB;
        $rows = $DB->get_records_sql("
            SELECT a.id, a.name, a.duedate, c.fullname, cm.id AS cmid
              FROM {assign} a
              JOIN {course} c           ON c.id  = a.course
              JOIN {course_modules} cm  ON cm.instance = a.id
              JOIN {modules} mo         ON mo.id = cm.module AND mo.name = 'assign'
              JOIN {enrol} e            ON e.courseid = c.id
              JOIN {user_enrolments} ue ON ue.enrolid = e.id AND ue.userid = :uid
         LEFT JOIN {assign_submission} sub
                   ON sub.assignment = a.id AND sub.userid = :uid2 AND sub.status = 'submitted'
             WHERE (a.duedate = 0 OR a.duedate > :now)
               AND sub.id IS NULL AND cm.visible = 1 AND c.visible = 1
             ORDER BY a.duedate ASC LIMIT 15
        ", ['uid'=>$userid,'uid2'=>$userid,'now'=>time()]);

        $out = [];
        foreach ($rows as $r) {
            $due = $r->duedate ? userdate($r->duedate,'%d %b %Y') : 'No deadline';
            $out[] = [
                'id'        => 'pending_assign_' . $r->id,
                'type'      => 'pending_assignment',
                'message'   => "Assignment \"{$r->name}\" in \"{$r->fullname}\" not submitted. Due: {$due}",
                'time'      => $due,
                'timestamp' => $r->duedate ?: time(),
                'url'       => (new \moodle_url('/mod/assign/view.php',['id'=>$r->cmid]))->out(false),
                'isread'    => false,
            ];
        }
        return $out;
    }

    // ── 7. Pending quizzes ───────────────────────────────────────
    private function get_pending_quizzes(int $userid): array {
        global $DB;
        $rows = $DB->get_records_sql("
            SELECT q.id, q.name, q.timeclose, c.fullname, cm.id AS cmid
              FROM {quiz} q
              JOIN {course} c           ON c.id = q.course
              JOIN {course_modules} cm  ON cm.instance = q.id
              JOIN {modules} mo         ON mo.id = cm.module AND mo.name = 'quiz'
              JOIN {enrol} e            ON e.courseid = c.id
              JOIN {user_enrolments} ue ON ue.enrolid = e.id AND ue.userid = :uid
         LEFT JOIN {quiz_attempts} qa
                   ON qa.quiz = q.id AND qa.userid = :uid2 AND qa.state = 'finished'
             WHERE (q.timeclose = 0 OR q.timeclose > :now)
               AND (q.timeopen  = 0 OR q.timeopen < :now2)
               AND qa.id IS NULL AND cm.visible = 1 AND c.visible = 1
             ORDER BY q.timeclose ASC LIMIT 15
        ", ['uid'=>$userid,'uid2'=>$userid,'now'=>time(),'now2'=>time()]);

        $out = [];
        foreach ($rows as $r) {
            $closes = $r->timeclose ? userdate($r->timeclose,'%d %b %Y') : 'No deadline';
            $out[] = [
                'id'        => 'pending_quiz_' . $r->id,
                'type'      => 'pending_quiz',
                'message'   => "Quiz \"{$r->name}\" in \"{$r->fullname}\" starts tomorrow at 10:00 AM.",
                'time'      => $closes,
                'timestamp' => $r->timeclose ?: time(),
                'url'       => (new \moodle_url('/mod/quiz/view.php',['id'=>$r->cmid]))->out(false),
                'isread'    => false,
            ];
        }
        return $out;
    }

    // ── 8. Incomplete modules ────────────────────────────────────
    private function get_incomplete_modules(int $userid): array {
        global $DB;
        $rows = $DB->get_records_sql("
            SELECT cm.id AS cmid, m.name AS module_type, c.fullname
              FROM {course_modules} cm
              JOIN {modules} m          ON m.id  = cm.module
              JOIN {course} c           ON c.id  = cm.course
              JOIN {enrol} e            ON e.courseid = c.id
              JOIN {user_enrolments} ue ON ue.enrolid = e.id AND ue.userid = :uid
         LEFT JOIN {course_modules_completion} cmc
                   ON cmc.coursemoduleid = cm.id AND cmc.userid = :uid2
             WHERE cm.visible = 1 AND c.visible = 1 AND cm.completion > 0
               AND (cmc.completionstate IS NULL OR cmc.completionstate = 0)
             ORDER BY c.fullname ASC LIMIT 15
        ", ['uid'=>$userid,'uid2'=>$userid]);

        $out = [];
        foreach ($rows as $r) {
            $cm    = get_coursemodule_from_id($r->module_type, $r->cmid);
            $title = $cm ? $cm->name : ucfirst($r->module_type).' activity';
            $out[] = [
                'id'        => 'incomplete_' . $r->cmid,
                'type'      => 'incomplete_module',
                'message'   => "Semester 3 results have been published.",
                'time'      => '',
                'timestamp' => 0,
                'url'       => (new \moodle_url('/mod/'.$r->module_type.'/view.php',['id'=>$r->cmid]))->out(false),
                'isread'    => false,
            ];
        }
        return $out;
    }

    // ── Unread count ─────────────────────────────────────────────
    private function get_unread_count(int $userid): int {
        global $DB;
        if (!$DB->get_manager()->table_exists('user_notifications')) return 0;
        return (int)$DB->count_records('user_notifications',['userid'=>$userid,'isread'=>0]);
    }
}
