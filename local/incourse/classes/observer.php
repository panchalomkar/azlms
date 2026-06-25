<?php
namespace local_incourse;

defined('MOODLE_INTERNAL') || die();

class observer {

    public static function user_created(\core\event\user_created $event) {
        global $DB;

        $userid = $event->objectid;

        // Fetch user
        $user = $DB->get_record('user', ['id' => $userid], '*', MUST_EXIST);

        // Skip if ID number already exists
        if (!empty($user->idnumber)) {
            return;
        }

        // Get last used 4-digit ID
        $sql = "
            SELECT idnumber
            FROM {user}
            WHERE idnumber REGEXP '^[0-9]{4}$'
            ORDER BY idnumber DESC
            LIMIT 1
        ";

        $last = $DB->get_record_sql($sql);

        if ($last && is_numeric($last->idnumber)) {
            $next = intval($last->idnumber) + 1;
        } else {
            $next = 1;
        }

        // Ensure 4-digit format with leading zeros
        $newid = str_pad($next, 4, '0', STR_PAD_LEFT);

        // Update user
        $DB->update_record('user', [
            'id' => $userid,
            'idnumber' => $newid
        ]);
    }
}
