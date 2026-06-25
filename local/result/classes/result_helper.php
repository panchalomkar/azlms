<?php
namespace local_result;
defined('MOODLE_INTERNAL') || die();

class result_helper {

    // ── GPA from Moodle grades ────────────────────────────────────
    public function get_gpa(int $userid): array {
        global $DB, $CFG;
        require_once($CFG->dirroot . '/lib/gradelib.php');

        $courses  = enrol_get_users_courses($userid, true);
        $total    = 0; $count = 0;
        $semesters = [];

        foreach ($courses as $course) {
            $gi = \grade_item::fetch_course_item($course->id);
            if (!$gi) continue;
            $grade = new \grade_grade(['itemid' => $gi->id, 'userid' => $userid]);
            $grade->grade_item = $gi;
            if ($gi->grademax <= 0) continue;
            $pct = (($grade->finalgrade ?? 0) / $gi->grademax) * 4.0;
            $total += $pct; $count++;

            // Group by course category as semester proxy
            $cat = $DB->get_field('course_categories', 'name', ['id' => $course->category]);
            $semesters[$cat][] = $pct;
        }

        $gpa = $count > 0 ? round($total / $count, 2) : null;

        // Build semester list (max 3)
        $sem_out = [];
        $i = 1;
        foreach ($semesters as $name => $gpas) {
            $sem_out[] = [
                'label' => 'Semester ' . $i,
                'gpa'   => round(array_sum($gpas) / count($gpas), 2),
            ];
            if ($i++ >= 3) break;
        }
        // Pad to 3 semesters
        while (count($sem_out) < 3) {
            $sem_out[] = ['label' => 'Semester ' . count($sem_out) + 1, 'gpa' => null];
        }

        $percent = $gpa !== null ? min(round(($gpa / 4.0) * 100), 100) : 0;

        return [
            'gpa'          => $gpa,
            'gpa_display'  => $gpa !== null ? number_format($gpa, 2) : null,
            'percent'      => $percent,
            'semesters'    => $sem_out,
            'has_data'     => $gpa !== null,
            'prev_gpa'     => null, // wire up if you store historical GPAs
        ];
    }

    // ── Attendance ────────────────────────────────────────────────
    public function get_attendance(int $userid): array {
        global $DB;

        // Works with mod_attendance plugin
        if (!$DB->get_manager()->table_exists('attendance_log')) {
            return ['has_data' => false, 'percent' => 0, 'display' => null];
        }

        $total = $DB->count_records_sql(
            "SELECT COUNT(*) FROM {attendance_log} al
              JOIN {attendance_sessions} s ON s.id = al.sessionid
             WHERE al.studentid = ?", [$userid]);

        $present = $DB->count_records_sql(
            "SELECT COUNT(*) FROM {attendance_log} al
              JOIN {attendance_statuses} st ON st.id = al.statusid
             WHERE al.studentid = ? AND st.acronym IN ('P','L')", [$userid]);

        if ($total == 0) return ['has_data' => false, 'percent' => 0, 'display' => null];

        $pct = round(($present / $total) * 100, 1);
        return ['has_data' => true, 'percent' => $pct, 'display' => $pct . '%'];
    }

    // ── Externship ────────────────────────────────────────────────
    public function get_externship(int $userid): array {
        global $DB;
        $total_required = 99;

        $sites      = $DB->get_records('externship_sites', ['userid' => $userid], 'startdate ASC');
        $timesheets = $DB->get_records('externship_timesheet', ['userid' => $userid], 'externdate ASC');

        $approved = (float)($DB->get_field_sql(
            "SELECT SUM(attendhrs) FROM {externship_timesheet}
              WHERE userid = ? AND status = 'Approved'", [$userid]) ?: 0);

        $pending = (float)($DB->get_field_sql(
            "SELECT SUM(attendhrs) FROM {externship_timesheet}
              WHERE userid = ? AND status = 'Pending'", [$userid]) ?: 0);

        $percent = $total_required > 0
            ? min(round(($approved / $total_required) * 100), 100)
            : 0;

        // Donut chart math
        $radius = 60; $circ = 2 * M_PI * $radius;
        $approved_dash  = $circ * ($approved / max($approved + $pending, 1));
        $pending_dash   = $circ * ($pending  / max($approved + $pending, 1));
        $site_count     = count($sites);
        $total_sites    = max($site_count, 1);

        // Greeting
        if ($percent >= 100)     $greeting = "Congratulations, you've completed your externship!";
        elseif ($percent >= 75)  $greeting = "Great job, you're almost done!";
        elseif ($percent >= 50)  $greeting = "You're halfway there!";
        else                     $greeting = "Keep up the momentum. You're doing great on your learning journey";

        // Sites formatted
        $sites_out = [];
        foreach ($sites as $s) {
            $sites_out[] = [
                'companyname' => $s->companyname,
                'address'     => $s->address,
                'phone'       => $s->phone,
                'supervisor'  => $s->supervisor,
                'startdate'   => date('m/d/Y', strtotime($s->startdate)),
            ];
        }

        // Timesheets formatted
        $ts_out = [];
        foreach ($timesheets as $t) {
            $ts_out[] = [
                'id'         => $t->id,
                'externdate' => date('d/m/Y', strtotime($t->externdate)),
                'starttime'  => date('h:i A', strtotime($t->starttime)),
                'endtime'    => date('h:i A', strtotime($t->endtime)),
                'attendhrs'  => number_format($t->attendhrs, 0) . ' Hrs',
                'attendhrs_raw' => $t->attendhrs,
                'schedhrs'   => number_format($t->schedhrs, 0) . ' Hrs',
                'status'     => $t->status,
                'approved'   => $t->status === 'Approved',
                'pending'    => $t->status === 'Pending',
                'rejected'   => $t->status === 'Rejected',
                'status_class' => strtolower($t->status),
            ];
        }

        return [
            'has_sites'      => !empty($sites),
            'sites'          => $sites_out,
            'has_timesheets' => !empty($timesheets),
            'timesheets'     => $ts_out,
            'total_required' => $total_required,
            'approved'       => $approved,
            'pending'        => $pending,
            'percent'        => $percent,
            'greeting'       => $greeting,
            'site_count'     => $site_count,
            // Donut
            'circ'           => round($circ, 2),
            'approved_dash'  => round($approved_dash, 2),
            'pending_dash'   => round($pending_dash, 2),
            // SVG progress circle
            'svg_circ'       => round($circ, 2),
            'svg_offset'     => round($circ - ($percent / 100) * $circ, 2),
            // pie segments for donut
            'approved_offset'=> 0,
            'pending_offset' => round($approved_dash, 2),
        ];
    }

    // ── User list for selector ─────────────────────────────────────
    public function get_user_list(int $actorid, bool $isadmin, bool $ismanager): array {
        global $DB, $USER;
        if ($isadmin) {
            return $DB->get_records_sql_menu(
                "SELECT id, CONCAT(firstname,' ',lastname) AS fullname
                   FROM {user} WHERE deleted=0 ORDER BY lastname ASC");
        }
        if ($ismanager) {
            $email = trim($USER->email);
            $users = $DB->get_records_sql_menu(
                "SELECT u.id, CONCAT(u.firstname,' ',u.lastname) AS fullname
                   FROM {user} u
                   JOIN {user_info_data} uid ON uid.userid = u.id
                   JOIN {user_info_field} uif ON uif.id = uid.fieldid
                  WHERE uif.shortname='manager' AND uid.data LIKE ?
                    AND u.deleted=0
                  ORDER BY u.lastname ASC", ['%' . $email . '%']);
            return $users ?: [$USER->id => fullname($USER)];
        }
        return [$actorid => fullname(\core_user::get_user($actorid))];
    }
}
