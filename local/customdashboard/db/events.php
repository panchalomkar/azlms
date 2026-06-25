<?php
defined('MOODLE_INTERNAL') || die();

$observers = [
    // Fires when the user logs in — counts as "active today"
    [
        'eventname' => '\core\event\user_loggedin',
        'callback'  => '\local_customdashboard\observer::update_streak',
    ],
    // Fires when the user views any course page
    [
        'eventname' => '\core\event\course_viewed',
        'callback'  => '\local_customdashboard\observer::update_streak',
    ],
    // Fires when the user views any activity/resource inside a course
    [
        'eventname' => '\core\event\course_module_viewed',
        'callback'  => '\local_customdashboard\observer::update_streak',
    ],
];
