<?php
// ================================================================
// File: local/notifications/api.php
// Fetches all notification types and returns JSON
// ================================================================
define('NO_MOODLE_COOKIES', false);
require_once('../../config.php');
require_login();
require_once($CFG->dirroot . '/local/notifications/classes/notification_helper.php');

global $USER;

header('Content-Type: application/json');
header('Cache-Control: no-cache');

try {
    $helper = new local_notifications\notification_helper();
    $data   = $helper->get_all($USER->id);
    echo json_encode(['status' => 'success'] + $data);
} catch (Throwable $e) {
    echo json_encode([
        'status'        => 'error',
        'notifications' => [],
        'unread_count'  => 0,
        'pending_count' => 0,
    ]);
}
exit;
