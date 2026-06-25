<?php
defined('MOODLE_INTERNAL') || die();
$capabilities = [
    'block/customdashboard:myaddinstance' => [
        'captype'      => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes'   => ['user' => CAP_ALLOW],
    ],
    'block/customdashboard:addinstance' => [
        'captype'      => 'write',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes'   => ['manager' => CAP_ALLOW],
    ],
];
