<?php
require_once('../config.php');
require_login();
global $DB, $USER;

$userid    = required_param('userid', PARAM_INT);
$entrytype = required_param('entrytype', PARAM_ALPHA);

$record = new stdClass();
$record->userid       = $userid;
$record->timecreated  = time();
$record->timemodified = time();

if ($entrytype === 'site') {
    $record->companyname = required_param('companyname', PARAM_TEXT);
    $record->address     = optional_param('address', '', PARAM_TEXT);
    $record->phone       = optional_param('phone', '', PARAM_TEXT);
    $record->supervisor  = optional_param('supervisor', '', PARAM_TEXT);
    $record->usertype = required_param('usertype', PARAM_RAW_TRIMMED);
    $rawdate = required_param('startdate', PARAM_RAW_TRIMMED);
    if (!$rawdate) {
        print_error('Please select a start date for the site.');
    }
    $record->startdate = $rawdate;

    // Insert site record
    $DB->insert_record('externship_sites', $record);

} elseif ($entrytype === 'timesheet') {
    // siteid is optional, only included if exists
    $record->siteid = optional_param('siteid', 0, PARAM_INT);

    $rawdate = required_param('externdate', PARAM_RAW_TRIMMED);
    if (!$rawdate) {
        print_error('Please select a date.');
    }
    $record->externdate = $rawdate;

    $record->starttime = required_param('starttime', PARAM_RAW_TRIMMED);
    $record->endtime   = required_param('endtime', PARAM_RAW_TRIMMED);
    $record->attendhrs = required_param('attendhrs', PARAM_FLOAT);
    $record->schedhrs  = required_param('schedhrs', PARAM_FLOAT);
    $record->status    = 'Pending';

    $DB->insert_record('externship_timesheet', $record);
}

redirect(new moodle_url('/dashboard/result.php', ['userid' => $userid]));
