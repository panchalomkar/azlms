<?php

require_once("../config.php");
require_once(__DIR__ . '/report_generator.php');

require_login();

$courseid = optional_param('courseid', 0, PARAM_INT);
$userfilter = optional_param('username', '', PARAM_RAW_TRIMMED);

// Get course options
$courses = $DB->get_records_menu('course', null, 'fullname ASC', 'id, fullname');

echo $OUTPUT->header();
echo $OUTPUT->heading('User Course Report');

// Filter Form
echo html_writer::start_tag('form', ['method' => 'get']);
echo html_writer::start_div();

echo 'Course: ';
echo html_writer::start_tag('select', ['name' => 'courseid']);
echo html_writer::tag('option', 'All Courses', ['value' => '0']);
foreach ($courses as $id => $name) {
    $selected = ($id == $courseid) ? ['selected' => 'selected'] : [];
    echo html_writer::tag('option', format_string($name), array_merge(['value' => $id], $selected));
}
echo html_writer::end_tag('select');

echo ' Username: ' . html_writer::empty_tag('input', [
    'type' => 'text',
    'name' => 'username',
    'value' => $userfilter
]);

echo html_writer::empty_tag('input', [
    'type' => 'submit',
    'value' => 'Filter'
]);

echo html_writer::end_div();
echo html_writer::end_tag('form');

// Generate Report
$report = new report_generator();
echo $report->generate_html($courseid, $userfilter);

echo $OUTPUT->footer();
