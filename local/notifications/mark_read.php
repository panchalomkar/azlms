<?php
// ================================================================
// File: local/notifications/mark_read.php
// AJAX endpoint to mark notifications as read
// ================================================================
require_once('../../config.php');
require_login();
require_sesskey();

global $USER, $DB;

$action = optional_param('action', '', PARAM_ALPHA);

header('Content-Type: application/json');

if ($action === 'markallread') {
    if ($DB->get_manager()->table_exists('user_notifications')) {
        $DB->set_field('user_notifications', 'isread', 1, ['userid' => $USER->id]);
    }
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
exit;
