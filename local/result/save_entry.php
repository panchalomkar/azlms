<?php
require_once('../../config.php');
require_login();
require_sesskey();

global $DB, $USER;

$userid    = required_param('userid',    PARAM_INT);
$entrytype = required_param('entrytype', PARAM_ALPHA);

$isadmin   = is_siteadmin();
$ismanager = user_has_role_assignment($USER->id, 9);

// Permission check
if (!$isadmin && !$ismanager && $userid !== $USER->id) {
    throw new moodle_exception('nopermissions', 'error');
}

$redirecturl = new moodle_url('/local/result/index.php', ['userid' => $userid]);

if ($entrytype === 'site') {
    $record = new stdClass();
    $record->userid      = $userid;
    $record->companyname = required_param('companyname', PARAM_TEXT);
    $record->address     = optional_param('address',    '', PARAM_TEXT);
    $record->phone       = optional_param('phone',      '', PARAM_TEXT);
    $record->supervisor  = optional_param('supervisor', '', PARAM_TEXT);
    $record->usertype    = optional_param('usertype',   'internal', PARAM_ALPHA);
    $record->startdate   = required_param('startdate',  PARAM_TEXT);
    $record->timecreated = time();
    $DB->insert_record('externship_sites', $record);

} elseif ($entrytype === 'timesheet') {
    $record = new stdClass();
    $record->userid      = $userid;
    $record->siteid      = optional_param('siteid',     0,   PARAM_INT);
    $record->externdate  = required_param('externdate', PARAM_TEXT);
    $record->starttime   = required_param('starttime',  PARAM_TEXT);
    $record->endtime     = required_param('endtime',    PARAM_TEXT);
    $record->attendhrs   = required_param('attendhrs',  PARAM_FLOAT);
    $record->schedhrs    = required_param('schedhrs',   PARAM_FLOAT);
    $record->status      = 'Pending';
    $record->timecreated = time();
    $DB->insert_record('externship_timesheet', $record);
}

redirect($redirecturl);
