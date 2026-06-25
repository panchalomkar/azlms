<?php
namespace local_result;
defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/externallib.php');

class external extends \external_api {

    // ── update_attendhrs ─────────────────────────────────────────
    public static function update_attendhrs_parameters() {
        return new \external_function_parameters([
            'id'        => new \external_value(PARAM_INT,   'Row ID'),
            'attendhrs' => new \external_value(PARAM_FLOAT, 'New hours'),
        ]);
    }
    public static function update_attendhrs(int $id, float $attendhrs): array {
        global $DB, $USER;
        self::validate_parameters(self::update_attendhrs_parameters(),
            ['id' => $id, 'attendhrs' => $attendhrs]);

        $row = $DB->get_record('externship_timesheet', ['id' => $id], '*', MUST_EXIST);
        if ($row->status === 'Approved') {
            return ['success' => false, 'message' => 'Already approved'];
        }
        $DB->set_field('externship_timesheet', 'attendhrs', $attendhrs, ['id' => $id]);
        return ['success' => true, 'message' => 'Updated'];
    }
    public static function update_attendhrs_returns() {
        return new \external_single_structure([
            'success' => new \external_value(PARAM_BOOL, 'Success'),
            'message' => new \external_value(PARAM_TEXT, 'Message'),
        ]);
    }

    // ── timesheet_action ─────────────────────────────────────────
    public static function timesheet_action_parameters() {
        return new \external_function_parameters([
            'id'     => new \external_value(PARAM_INT,  'Row ID'),
            'status' => new \external_value(PARAM_TEXT, 'Approved|Rejected'),
        ]);
    }
    public static function timesheet_action(int $id, string $status): array {
        global $DB, $USER;
        self::validate_parameters(self::timesheet_action_parameters(),
            ['id' => $id, 'status' => $status]);

        if (!in_array($status, ['Approved', 'Rejected'])) {
            return ['success' => false, 'message' => 'Invalid status'];
        }
        if (!is_siteadmin() && !user_has_role_assignment($USER->id, 9)) {
            return ['success' => false, 'message' => 'Permission denied'];
        }
        $DB->set_field('externship_timesheet', 'status', $status, ['id' => $id]);
        return ['success' => true, 'message' => $status];
    }
    public static function timesheet_action_returns() {
        return new \external_single_structure([
            'success' => new \external_value(PARAM_BOOL, 'Success'),
            'message' => new \external_value(PARAM_TEXT, 'Message'),
        ]);
    }
}
