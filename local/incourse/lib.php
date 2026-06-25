<?php
defined('MOODLE_INTERNAL') || die();

function local_incourse_extend_navigation_course($navigation, $course, $context) {
    global $COURSE;

    // Only show if user can view grades or manage course.
    if (!has_capability('moodle/course:update', $context)) {
        return;
    }

    // Create URL.
    $url = new moodle_url('/local/incourse/forum_grade.php', [
        'courseid' => $COURSE->id
    ]);

    // Add link to course navigation tabs.
    $navigation->add(
        get_string('forumgrades', 'local_incourse', 'Forum Grades'),
        $url,
        navigation_node::TYPE_CUSTOM,
        'forumgrades'
    );
}
    
  