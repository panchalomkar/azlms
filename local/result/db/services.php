<?php
defined('MOODLE_INTERNAL') || die();
$functions = [
    'local_result_update_attendhrs' => [
        'classname'     => 'local_result\external',
        'methodname'    => 'update_attendhrs',
        'description'   => 'Update attend hours inline',
        'type'          => 'write',
        'ajax'          => true,
        'loginrequired' => true,
    ],
    'local_result_timesheet_action' => [
        'classname'     => 'local_result\external',
        'methodname'    => 'timesheet_action',
        'description'   => 'Approve or reject timesheet row',
        'type'          => 'write',
        'ajax'          => true,
        'loginrequired' => true,
    ],
];
