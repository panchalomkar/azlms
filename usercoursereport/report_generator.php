<?php

//namespace usercoursedata;

defined('MOODLE_INTERNAL') || die();

class report_generator {

   public function generate_html(int $courseid = 0, string $userfilter = ''): string {
    global $DB;

    $params = [];

    $sql = "
        SELECT
            ROW_NUMBER() OVER () AS uniqueid,
            u.id AS userid,
            u.firstname,
            u.lastname,
            u.email,
            u.timecreated AS user_created,
            c.id AS courseid,
            c.fullname AS course_name,
            ue.timecreated AS enrolment_date,
            cmc.timemodified AS activity_completed
        FROM {user} u
        JOIN {user_enrolments} ue ON ue.userid = u.id
        JOIN {enrol} e ON e.id = ue.enrolid
        JOIN {course} c ON c.id = e.courseid
        LEFT JOIN {course_modules_completion} cmc ON cmc.userid = u.id
        WHERE 1=1
    ";

    if ($courseid > 0) {
        $sql .= " AND c.id = :courseid";
        $params['courseid'] = $courseid;
    }

    if ($userfilter) {
        $sql .= " AND (u.firstname LIKE :userfilter OR u.lastname LIKE :userfilter OR u.email LIKE :userfilter)";
        $params['userfilter'] = '%' . $userfilter . '%';
    }

    $records = $DB->get_records_sql($sql, $params);

    // (HTML output logic stays the same)


        $output = html_writer::start_tag('table', ['class' => 'generaltable']);
        $output .= html_writer::start_tag('tr');
        foreach (['User ID', 'Name', 'Email', 'User Created', 'Course', 'Enrolment Date', 'Activity Completion'] as $heading) {
            $output .= html_writer::tag('th', $heading);
        }
        $output .= html_writer::end_tag('tr');

        foreach ($records as $r) {
            $output .= html_writer::start_tag('tr');
            $output .= html_writer::tag('td', $r->userid);
            $output .= html_writer::tag('td', $r->firstname . ' ' . $r->lastname);
            $output .= html_writer::tag('td', $r->email);
            $output .= html_writer::tag('td', userdate($r->user_created));
            $output .= html_writer::tag('td', $r->course_name);
            $output .= html_writer::tag('td', $r->enrolment_date ? userdate($r->enrolment_date) : '-');
            $output .= html_writer::tag('td', $r->activity_completed ? userdate($r->activity_completed) : '-');
            $output .= html_writer::end_tag('tr');
        }

        $output .= html_writer::end_tag('table');
        return $output;
    }
}
