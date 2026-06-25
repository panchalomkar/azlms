<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) { // Only show to admins
    $ADMIN->add('reports',
        new admin_externalpage(
            'local_myddleware',                        // Unique name
            'Contact Form Report',  // Title shown in Reports list
            new moodle_url('/request_report.php')    // Your custom report page
        )
    );
}
