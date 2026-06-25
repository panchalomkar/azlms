<?php
defined('MOODLE_INTERNAL') || die();

$functions = [
    'local_courses_get_completion_popup' => [
        'classname'   => 'local_courses\external',
        'methodname'  => 'get_completion_popup',
        'description' => 'Get completion popup data',
        'type'        => 'read',
        'ajax'        => true,
        'loginrequired' => true,
    ],
    'local_courses_get_improvement_suggestions' => [
        'classname'   => 'local_courses\external',
        'methodname'  => 'get_improvement_suggestions',
        'description' => 'Get improvement suggestions',
        'type'        => 'read',
        'ajax'        => true,
        'loginrequired' => true,
    ],
];
