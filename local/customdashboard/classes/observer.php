<?php
namespace local_customdashboard;
defined('MOODLE_INTERNAL') || die();

class observer {

    /**
     * Shared site timezone — must match get_streak() in dashboard_data.php.
     * Both use date strings ('Y-m-d') in this timezone so comparisons always work.
     */
    private static function site_tz(): \DateTimeZone {
        $tzname = \core_date::get_server_timezone();
        try {
            return new \DateTimeZone($tzname);
        } catch (\Exception $e) {
            return new \DateTimeZone('Asia/Kolkata');
        }
    }

    public static function update_streak(\core\event\base $event) {
        global $DB;

        $userid = $event->userid;
        if (empty($userid) || isguestuser($userid)) {
            return;
        }

        $tz        = self::site_tz();
        $now       = new \DateTime('now', $tz);
        $today     = $now->format('Y-m-d');                    // e.g. "2026-06-25"
        $yesterday = (clone $now)->modify('-1 day')->format('Y-m-d');

        $record = $DB->get_record('local_customdashboard_streak', ['userid' => $userid]);

        if (!$record) {
            // First ever activity — create record.
            $new = new \stdClass();
            $new->userid         = $userid;
            $new->currentstreak  = 1;
            $new->longeststreak  = 1;
            $new->lastactivedate = $today;           // store as 'Y-m-d' string
            $new->activedays     = json_encode([$today]);
            $new->timecreated    = time();
            $new->timemodified   = time();
            $DB->insert_record('local_customdashboard_streak', $new);
            return;
        }

        // Decode existing active days (may be old timestamp array or new date string array).
        $activedays = self::decode_activedays($record->activedays ?? '[]', $tz);

        // Already counted today — nothing to do.
        if ($record->lastactivedate === $today || in_array($today, $activedays)) {
            // Ensure today IS in activedays (migration safety).
            if (!in_array($today, $activedays)) {
                $activedays[] = $today;
                $record->activedays   = json_encode(self::prune_to_this_week($activedays, $tz));
                $record->timemodified = time();
                $DB->update_record('local_customdashboard_streak', $record);
            }
            return;
        }

        // New day — update streak.
        if ($record->lastactivedate === $yesterday) {
            $record->currentstreak += 1;
        } else {
            $record->currentstreak = 1; // Gap — streak broken.
        }

        $activedays[] = $today;

        $record->longeststreak  = max($record->longeststreak, $record->currentstreak);
        $record->lastactivedate = $today;
        $record->activedays     = json_encode(self::prune_to_this_week($activedays, $tz));
        $record->timemodified   = time();
        $DB->update_record('local_customdashboard_streak', $record);
    }

    /**
     * Handle both old format (array of int timestamps) and new format
     * (array of 'Y-m-d' strings) so existing installs migrate automatically.
     */
    private static function decode_activedays(string $json, \DateTimeZone $tz): array {
        $raw = json_decode($json, true) ?: [];
        $out = [];
        foreach ($raw as $val) {
            if (is_int($val) || (is_string($val) && ctype_digit($val))) {
                // Old format: Unix timestamp — convert to date string.
                $dt    = new \DateTime('@' . (int)$val);
                $dt->setTimezone($tz);
                $out[] = $dt->format('Y-m-d');
            } elseif (is_string($val) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $val)) {
                $out[] = $val;
            }
        }
        return array_unique($out);
    }

    /**
     * Keep only date strings that fall within the current Mon–Sun week
     * (in site timezone) so the array stays small.
     */
    private static function prune_to_this_week(array $days, \DateTimeZone $tz): array {
        $monday = new \DateTime('monday this week', $tz);
        $sunday = (clone $monday)->modify('+6 days');

        return array_values(array_filter($days, function($d) use ($monday, $sunday) {
            try {
                $dt = new \DateTime($d);
                return $dt >= $monday && $dt <= $sunday;
            } catch (\Exception $e) {
                return false;
            }
        }));
    }
}
