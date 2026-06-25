<?php
defined('MOODLE_INTERNAL') || die();

$observers = [
    [
        'eventname' => '\core\event\user_created',
        'callback'  => '\local_incourse\observer::user_created',
        'priority'  => 9999
    ]
];
