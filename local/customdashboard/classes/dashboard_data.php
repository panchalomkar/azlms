<?php
namespace local_customdashboard;
defined('MOODLE_INTERNAL') || die();

class dashboard_data {

    public static function get_dashboard_data($userid) {
        global $CFG;
        require_once($CFG->libdir . '/completionlib.php');

        $user    = \core_user::get_user($userid);
        $courses = enrol_get_users_courses($userid, true, '*', 'visible DESC, sortorder ASC');

        $streak   = self::get_streak($userid);
        $sessions = self::get_sessions_list($courses, $userid);
        $continue = self::get_continue_learning($userid);

        $monday     = strtotime('monday this week midnight');
        $lastmonday = strtotime('-1 week', $monday);
        $monthstart = strtotime('first day of this month midnight');
        $now        = time();

        $charts = [
            'weekly'   => self::get_chart_html($userid, $monday,     $now,        'daily'),
            'lastweek' => self::get_chart_html($userid, $lastmonday, $monday - 1, 'daily'),
            'monthly'  => self::get_chart_html($userid, $monthstart, $now,        'daily_of_month'),
        ];

        $insights = [
            'weekly'   => self::get_insights($userid, $courses, $monday,     $now),
            'lastweek' => self::get_insights($userid, $courses, $lastmonday, $monday - 1),
            'monthly'  => self::get_insights($userid, $courses, $monthstart, $now),
        ];

        return [
            'greeting'          => self::get_greeting(),
            'username'          => $user->firstname,
            'streak'            => $streak,
            'hasstreak'         => $streak['current'] > 0,
            'continuelearning'  => $continue,
            'hascontinue'       => !empty($continue),
            'insights'          => $insights['weekly'],
            'insights_weekly'   => json_encode($insights['weekly']),
            'insights_lastweek' => json_encode($insights['lastweek']),
            'insights_monthly'  => json_encode($insights['monthly']),
            'chart_weekly'      => $charts['weekly'],
            'chart_lastweek'    => $charts['lastweek'],
            'chart_monthly'     => $charts['monthly'],
            'sessions'          => $sessions,
            'hassessions'       => !empty($sessions),
            'freshuser'         => empty($courses),
            'viewallurl'        => (new \moodle_url('/my/courses.php'))->out(),
            'enrolurl'          => (new \moodle_url('/course/index.php'))->out(),
        ];
    }

    // ------------------------------------------------------------------ //
    //  GREETING — hardcoded IST (site timezone)
    // ------------------------------------------------------------------ //

    protected static function get_greeting(): string {
        $hour = (int)(new \DateTime('now', new \DateTimeZone('Asia/Kolkata')))->format('H');
        if ($hour >= 5  && $hour < 12) return 'Good Morning';
        if ($hour >= 12 && $hour < 17) return 'Good Afternoon';
        if ($hour >= 17 && $hour < 21) return 'Good Evening';
        return 'Good Night';
    }

    // ------------------------------------------------------------------ //
    //  STREAK — uses date strings; today is ALWAYS marked active if the
    //  user has any streak record (they're viewing the page right now).
    // ------------------------------------------------------------------ //

public static function get_streak($userid): array {
    global $DB;

    $tz       = new \DateTimeZone('Asia/Kolkata');
    $now      = new \DateTime('now', $tz);
    $today    = $now->format('Y-m-d');

    // Midnight timestamps in IST for lastactivedate column (still INT)
    $todayMidnight     = (clone $now)->setTime(0,0,0)->getTimestamp();
    $yesterdayMidnight = (clone $now)->modify('-1 day')->setTime(0,0,0)->getTimestamp();

    $mondayDt  = new \DateTime('monday this week midnight', $tz);
    $weekstart = $mondayDt->getTimestamp();
    $weekend   = $weekstart + (7 * DAYSECS);

    $record  = $DB->get_record('local_customdashboard_streak', ['userid' => $userid]);
    $current = $record ? (int) $record->currentstreak : 0;

    // ── Source of truth for dots: logstore covers every page visit ──────
    $activedates = [];
    if ($DB->get_manager()->table_exists('logstore_standard_log')) {
        $timestamps = $DB->get_fieldset_sql(
            "SELECT timecreated
               FROM {logstore_standard_log}
              WHERE userid = :userid
                AND timecreated >= :start
                AND timecreated <  :end
              ORDER BY timecreated ASC",
            ['userid' => $userid, 'start' => $weekstart, 'end' => $weekend]
        );
        foreach ($timestamps as $ts) {
            $dt = (new \DateTime('@' . $ts))->setTimezone($tz);
            $activedates[$dt->format('Y-m-d')] = true;
        }
    }

    // Always mark today — user is active right now.
    $activedates[$today] = true;

    // ── Update DB if today is newly active ──────────────────────────────
    // Convert stored INT lastactivedate back to a date string for comparison.
    $storedDate = ($record && $record->lastactivedate)
        ? (new \DateTime('@' . $record->lastactivedate))->setTimezone($tz)->format('Y-m-d')
        : '';

    $storedDays = $record
        ? self::decode_activedays_static($record->activedays ?? '[]', $tz)
        : [];

    if ($storedDate !== $today) {
        // Calculate new streak count.
        if ($storedDate === (clone $now)->modify('-1 day')->format('Y-m-d')) {
            $current = (int) ($record->currentstreak ?? 0) + 1;
        } else if ($storedDate !== $today) {
            $current = $record ? (int) $record->currentstreak : 1;
            // Only reset to 1 if there's a real gap (not just first load today)
            if ($storedDate < (clone $now)->modify('-1 day')->format('Y-m-d')) {
                $current = 1;
            }
        }

        $merged = array_values(array_unique(
            array_merge($storedDays, array_keys($activedates))
        ));

        if (!$record) {
            $new                 = new \stdClass();
            $new->userid         = $userid;
            $new->currentstreak  = 1;
            $new->longeststreak  = 1;
            $new->lastactivedate = $todayMidnight;   // INT ✓
            $new->activedays     = json_encode($merged);
            $new->timecreated    = time();
            $new->timemodified   = time();
            $DB->insert_record('local_customdashboard_streak', $new);
            $current = 1;
        } else {
            $record->currentstreak  = $current;
            $record->longeststreak  = max((int) $record->longeststreak, $current);
            $record->lastactivedate = $todayMidnight;   // INT ✓
            $record->activedays     = json_encode($merged);
            $record->timemodified   = time();
            $DB->update_record('local_customdashboard_streak', $record);
        }
    }

    // ── Build Mon–Sun dot grid ──────────────────────────────────────────
    $daylabels = ['M', 'T', 'W', 'TH', 'F', 'S', 'S'];
    $days      = [];
    for ($i = 0; $i < 7; $i++) {
        $daystr = (clone $mondayDt)->modify("+{$i} days")->format('Y-m-d');
        $days[] = [
            'completed' => isset($activedates[$daystr]),
            'label'     => $daylabels[$i],
        ];
    }

    return ['current' => $current, 'days' => $days];
}

/**
 * Handles both old format (int timestamps) and new format (Y-m-d strings).
 */
private static function decode_activedays_static(string $json, \DateTimeZone $tz): array {
    $raw = json_decode($json, true) ?: [];
    $out = [];
    foreach ($raw as $val) {
        if (is_int($val) || (is_string($val) && ctype_digit($val))) {
            $out[] = (new \DateTime('@' . (int)$val))->setTimezone($tz)->format('Y-m-d');
        } elseif (is_string($val) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $val)) {
            $out[] = $val;
        }
    }
    return array_values(array_unique($out));
}

    /**
     * Handles both old format (int timestamps) and new format (Y-m-d strings).
     * Static duplicate of observer's private method so dashboard_data
     * can be called independently.
     */

    // ------------------------------------------------------------------ //
    //  CONTINUE LEARNING — with actual course image
    // ------------------------------------------------------------------ //

    public static function get_continue_learning($userid): ?array {
        global $DB, $CFG;
        require_once($CFG->libdir . '/completionlib.php');

        $sql = "SELECT c.*, ul.timeaccess
                  FROM {course} c
                  JOIN {user_lastaccess} ul ON ul.courseid = c.id AND ul.userid = :userid
                 WHERE c.id <> :siteid AND c.visible = 1
              ORDER BY ul.timeaccess DESC";
        $accessed = $DB->get_records_sql($sql, ['userid' => $userid, 'siteid' => SITEID], 0, 20);

        foreach ($accessed as $course) {
            $ci       = new \completion_info($course);
            $progress = $ci->is_enabled()
                ? \core_completion\progress::get_course_progress_percentage($course, $userid)
                : null;

            if ($progress !== null && $progress >= 100) continue;

            $pct = ($progress !== null) ? round($progress) : 0;

            return [
                'coursename' => format_string($course->fullname),
                'lessoninfo' => self::get_next_incomplete_activity($course, $userid, $ci),
                'progress'   => $pct,
                'courseurl'  => (new \moodle_url('/course/view.php', ['id' => $course->id]))->out(),
                'courseimage'=> self::get_course_image($course),
            ];
        }
        return null;
    }

    /**
     * Returns the course overview image URL, or empty string if none set.
     * Uses Moodle's pluginfile API — works with any storage backend.
     */
    protected static function get_course_image($course): string {
        $context = \context_course::instance($course->id);
        $fs      = get_file_storage();
        $files   = $fs->get_area_files(
            $context->id, 'course', 'overviewfiles', 0,
            'filename', false
        );
        foreach ($files as $file) {
            if ($file->is_valid_image()) {
                return \moodle_url::make_pluginfile_url(
                    $file->get_contextid(),
                    $file->get_component(),
                    $file->get_filearea(),
                    null,
                    $file->get_filepath(),
                    $file->get_filename()
                )->out();
            }
        }
        return ''; // No image set — template shows SVG placeholder.
    }

    protected static function get_next_incomplete_activity($course, $userid, $ci = null): string {
        if ($ci === null) $ci = new \completion_info($course);
        if (!$ci->is_enabled()) return format_string($course->fullname);

        $activities = $ci->get_activities();
        $total = count($activities);
        $index = 0;
        foreach ($activities as $cm) {
            $index++;
            $data = $ci->get_data($cm, false, $userid);
            if ($data->completionstate == COMPLETION_INCOMPLETE) {
                return "Lesson {$index} of {$total} | " . format_string($cm->name);
            }
        }
        return format_string($course->fullname);
    }

    // ------------------------------------------------------------------ //
    //  INSIGHTS — accepts a time window so we can pre-render per period
    // ------------------------------------------------------------------ //

    public static function get_insights($userid, $courses, $start, $end): array {
        $courseids = array_keys($courses);
        $count     = count($courses);

        return [
            'courses'    => $count > 0 ? sprintf('%02d', $count) : null,
            'hours'      => $count > 0 ? self::get_hours_learned($userid, $start, $end) : null,
            'attendance' => $count > 0 ? self::get_attendance_percentage($userid, $courseids) : null,
            'quizscore'  => $count > 0 ? self::get_quiz_average($userid, $courseids) : null,
        ];
    }

    protected static function get_hours_learned($userid, $start, $end): string {
        global $DB;
        if (!$DB->get_manager()->table_exists('logstore_standard_log')) return '0';

        $sql   = "SELECT timecreated FROM {logstore_standard_log}
                   WHERE userid = :userid AND timecreated BETWEEN :start AND :end
                ORDER BY timecreated ASC";
        $times = array_values($DB->get_fieldset_sql($sql, [
            'userid' => $userid, 'start' => $start, 'end' => $end,
        ]));

        $total = 0;
        for ($i = 1; $i < count($times); $i++) {
            $gap   = $times[$i] - $times[$i - 1];
            $total += ($gap <= 300) ? $gap : 60;
        }
        return round($total / 3600, 1);
    }

    /**
     * ATTENDANCE — uses the exact logic provided:
     *   P / E  → full session hours
     *   L      → 50% of session hours
     *   A      → 0
     * Percentage = (attended_hrs / total_possible_hrs) * 100
     */
    protected static function get_attendance_percentage($userid, $courseids): string {
        global $DB;

        if (empty($courseids) || !$DB->get_manager()->table_exists('attendance_sessions')) {
            return '0%';
        }

        list($insql, $params) = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED);
        $params['uid'] = $userid;

        $sql = "SELECT s.id, s.duration, al.statusid
                  FROM {attendance_sessions} s
                  JOIN {attendance_log} al ON al.sessionid = s.id
                  JOIN {attendance} a ON a.id = s.attendanceid
                 WHERE al.studentid = :uid AND a.course $insql";

        $sessions = $DB->get_records_sql($sql, $params);

        if (empty($sessions)) return '0%';

        $atdhrs            = 0;
        $total_possible_hrs = 0;

        foreach ($sessions as $sess) {
            $duration_in_hours = $sess->duration / 3600;

            $status = $DB->get_record('attendance_statuses',
                ['id' => $sess->statusid], 'acronym');

            if ($status) {
                switch ($status->acronym) {
                    case 'P': // Present
                    case 'E': // Excused
                        $atdhrs += $duration_in_hours;
                        break;
                    case 'L': // Late — 50%
                        $atdhrs += $duration_in_hours * 0.5;
                        break;
                    case 'A': // Absent
                        break;
                }
            }

            $total_possible_hrs += $duration_in_hours;
        }

        $atdperc = ($total_possible_hrs > 0)
            ? round(($atdhrs / $total_possible_hrs) * 100, 2)
            : 0;

        return $atdperc . '%';
    }

    protected static function get_quiz_average($userid, $courseids): string {
        global $DB;
        if (empty($courseids)) return '0%';

        list($in, $p) = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED);
        $p['userid'] = $userid;
        $sql = "SELECT qg.grade, q.grade AS maxgrade
                  FROM {quiz_grades} qg JOIN {quiz} q ON q.id = qg.quiz
                 WHERE qg.userid = :userid AND q.course $in";
        $rows = $DB->get_records_sql($sql, $p);
        $pcts = [];
        foreach ($rows as $r) {
            if ($r->maxgrade > 0) $pcts[] = ($r->grade / $r->maxgrade) * 100;
        }
        return empty($pcts) ? '0%' : round(array_sum($pcts) / count($pcts)) . '%';
    }

    // ------------------------------------------------------------------ //
    //  CHART
    // ------------------------------------------------------------------ //

    public static function get_chart_html($userid, $start, $end, $mode): string {
        global $OUTPUT;

        $labels  = [];
        $values  = [];
        $hasdata = false;

        if ($mode === 'daily') {
            $daynames = ['MON','TUE','WED','THU','FRI','SAT','SUN'];
            for ($i = 0; $i < 7; $i++) {
                $s = $start + ($i * DAYSECS);
                $e = $s + DAYSECS;
                $h = (float) self::get_hours_learned($userid, $s, $e);
                $labels[] = $daynames[$i];
                $values[] = $h;
                if ($h > 0) $hasdata = true;
            }
        } elseif ($mode === 'daily_of_month') {
            $day = $start;
            while ($day <= $end) {
                $e = $day + DAYSECS;
                $h = (float) self::get_hours_learned($userid, $day, $e);
                $labels[] = date('d', $day);
                $values[] = $h;
                if ($h > 0) $hasdata = true;
                $day = $e;
            }
        }

        if (!$hasdata) return '';

       $series = new \core\chart_series('Hours', $values);
$series = new \core\chart_series('Hours', $values);

$chart = new \core\chart_bar();
$chart->add_series($series);

$chart->set_labels($labels);
$chart->set_horizontal(false);

return $OUTPUT->render($chart);
    }

    // ------------------------------------------------------------------ //
    //  SESSIONS LIST
    // ------------------------------------------------------------------ //

    public static function get_sessions_list($courses, $userid, $limit = 3): array {
        global $CFG, $DB;
        require_once($CFG->libdir . '/completionlib.php');

        $sessions = [];
        $count    = 0;

        foreach ($courses as $course) {
            if ($count >= $limit) break;

            $ci       = new \completion_info($course);
            $progress = $ci->is_enabled()
                ? \core_completion\progress::get_course_progress_percentage($course, $userid)
                : null;

            if ($progress === null) {
                $lastaccess = $DB->get_field('user_lastaccess', 'timeaccess',
                    ['userid' => $userid, 'courseid' => $course->id]);
                $status = $lastaccess ? 'Ongoing'    : 'Not started';
                $cls    = $lastaccess ? 'status-ongoing' : 'status-notstarted';
            } elseif ($progress >= 100) {
                $status = 'Completed';   $cls = 'status-completed';
            } elseif ($progress > 0) {
                $status = 'Ongoing';    $cls = 'status-ongoing';
            } else {
                $lastaccess = $DB->get_field('user_lastaccess', 'timeaccess',
                    ['userid' => $userid, 'courseid' => $course->id]);
                $status = $lastaccess ? 'Ongoing'    : 'Not started';
                $cls    = $lastaccess ? 'status-ongoing' : 'status-notstarted';
            }

            $sessions[] = [
                'name'        => format_string($course->fullname),
                'duedate'     => $course->enddate
                    ? userdate($course->enddate, '%b %d, %y')
                    : 'No due date',
                'status'      => $status,
                'statusclass' => $cls,
                'url'         => (new \moodle_url('/course/view.php', ['id' => $course->id]))->out(),
            ];
            $count++;
        }
        return $sessions;
    }
}
