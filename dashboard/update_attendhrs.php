<?php
require_once('../config.php');
require_login();
global $DB, $USER;

$id = required_param('id', PARAM_INT);
$attendhrs = required_param('attendhrs', PARAM_FLOAT);

// Fetch timesheet
$timesheet = $DB->get_record('externship_timesheet', ['id' => $id], '*', MUST_EXIST);

// Only allow edit if not approved
if($timesheet->status === 'Approved') {
    echo json_encode(['success' => false, 'msg' => 'Already approved']);
    exit;
}

// Update value
$timesheet->attendhrs = $attendhrs;
$DB->update_record('externship_timesheet', $timesheet);

echo json_encode(['success' => true]);
