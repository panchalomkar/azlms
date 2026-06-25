<?php
require_once('../config.php');
require_login();
require_capability('moodle/site:config', context_system::instance());

global $DB;

$id     = required_param('id', PARAM_INT);
$status = required_param('status', PARAM_ALPHA);

$record = $DB->get_record('externship_timesheet', ['id' => $id], '*', MUST_EXIST);
$record->status = $status;
$record->timemodified = time();

$DB->update_record('externship_timesheet', $record);

redirect($_SERVER['HTTP_REFERER'], "Timesheet updated!", 2);
