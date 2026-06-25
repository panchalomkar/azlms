<?php
require_once(__DIR__ . '/../../config.php');
require_login();

// Teacher/admin only
if (!has_capability('moodle/course:update', context_system::instance())) {
    throw new moodle_exception('nopermissions');
}

$filename = required_param('file', PARAM_FILE);

// ✅ CORRECT folder
$filepath = $CFG->dataroot . '/incourse_grades/' . $filename;

if (!file_exists($filepath)) {
    print_error('filenotfound', 'error');
}

@ob_end_clean();

$mime = mime_content_type($filepath);

header('Content-Type: ' . $mime);
header('Content-Disposition: inline; filename="' . basename($filename) . '"');
header('Content-Length: ' . filesize($filepath));

readfile($filepath);
exit;
