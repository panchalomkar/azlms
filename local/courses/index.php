<?php
require_once('../../config.php');
require_once($CFG->dirroot . '/local/courses/classes/course_helper.php');

require_login();

$PAGE->set_url(new moodle_url('/local/courses/index.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('allcourses', 'local_courses'));
$PAGE->set_pagelayout('base');

// Tabler icons
$PAGE->requires->css(
    new moodle_url('https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css')
);

$filter = optional_param('filter', 'all', PARAM_ALPHA);

$helper  = new local_courses\course_helper();
$courses = $helper->get_user_courses($USER->id, $filter);

// Build 6 skeleton placeholders (matches 3-column grid × 2 rows)
$skeletons = array_fill(0, 6, ['s' => true]);

$templatedata = [
    'courses'           => array_values($courses),
    'filter'            => $filter,
    'filter_all'        => $filter === 'all',
    'filter_notstarted' => $filter === 'notstarted',
    'filter_ongoing'    => $filter === 'ongoing',
    'filter_completed'  => $filter === 'completed',
    'wwwroot'           => $CFG->wwwroot,
    'hascourses'        => !empty($courses),
    'skeletons'         => $skeletons,
];

echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_courses/courses', $templatedata);
echo $OUTPUT->footer();
