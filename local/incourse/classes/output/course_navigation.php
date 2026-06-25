<?php
namespace local_incourse\output;

use moodle_url;
use core_course\output\course_navigation as core_navigation;
use renderable;

class course_navigation extends core_navigation implements renderable {

    public function get_primary_actions() {
        $actions = parent::get_primary_actions();

        // Add new action (tab)
        $actions[] = [
            'key' => 'forumgrades',
            'text' => get_string('forumgrades', 'local_incourse', 'Forum Grades'),
            'url' => new moodle_url('/local/incourse/forum_grade.php', [
                'courseid' => $this->course->id
            ]),
            'isactive' => false
        ];

        return $actions;
    }
}
