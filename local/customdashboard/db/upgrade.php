<?php
defined('MOODLE_INTERNAL') || die();

function xmldb_local_customdashboard_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager();

    if ($oldversion < 2026061900) {
        $table = new xmldb_table('local_customdashboard_streak');
        $field = new xmldb_field('activedays', XMLDB_TYPE_TEXT, null, null, null, null, null, 'lastactivedate');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        upgrade_plugin_savepoint(true, 2026061900, 'local', 'customdashboard');
    }

    return true;
}
