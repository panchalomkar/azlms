<?php
defined('MOODLE_INTERNAL') || die();

class block_customdashboard extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_customdashboard');
    }

    public function applicable_formats() {
        return ['my' => true];
    }

    public function instance_allow_multiple() {
        return false;
    }

    public function hide_header() {
        return true;
    }

    public function get_content() {
        global $OUTPUT, $USER, $CFG;

        if ($this->content !== null) {
            return $this->content;
        }

        require_once($CFG->dirroot . '/local/customdashboard/classes/dashboard_data.php');

        $this->content         = new stdClass();
        $this->content->footer = '';

        $data = \local_customdashboard\dashboard_data::get_dashboard_data($USER->id);

        $this->content->text = $OUTPUT->render_from_template('block_customdashboard/content', $data);

        return $this->content;
    }
}
