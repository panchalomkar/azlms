<?php
namespace local_courses;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/externallib.php');
require_once($CFG->libdir . '/gradelib.php');
require_once($CFG->dirroot . '/lib/completionlib.php');

class external extends \external_api {

    // ── get_completion_popup ─────────────────────────────────────
    public static function get_completion_popup_parameters() {
        return new \external_function_parameters([
            'courseid' => new \external_value(PARAM_INT, 'Course ID'),
        ]);
    }

    public static function get_completion_popup(int $courseid): array {
        global $USER, $DB, $CFG;

        self::validate_parameters(
            self::get_completion_popup_parameters(),
            ['courseid' => $courseid]
        );

        $course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);

        // ── Final grade ────────────────────────────────────────
        $finalscore = 0;
        $gi = \grade_item::fetch_course_item($courseid);
        if ($gi && $gi->grademax > 0) {
            $grade = new \grade_grade(['itemid' => $gi->id, 'userid' => $USER->id]);
            $grade->grade_item = $gi;
            $finalscore = (int) round(
                (($grade->finalgrade ?? 0) / $gi->grademax) * 100
            );
        }

        // ── Quiz accuracy ──────────────────────────────────────
        $quizaccuracy = 0;
        $quizzes = $DB->get_records('quiz', ['course' => $courseid]);
        if (!empty($quizzes)) {
            $total = 0; $count = 0;
            foreach ($quizzes as $quiz) {
                $attempt = $DB->get_record_sql(
                    "SELECT sumgrades FROM {quiz_attempts}
                      WHERE quiz = ? AND userid = ? AND state = 'finished'
                      ORDER BY sumgrades DESC LIMIT 1",
                    [$quiz->id, $USER->id]
                );
                if ($attempt && $quiz->sumgrades > 0) {
                    $total += ($attempt->sumgrades / $quiz->sumgrades) * 100;
                    $count++;
                }
            }
            if ($count > 0) $quizaccuracy = (int) round($total / $count);
        }

        // ── Certificate URL ────────────────────────────────────
        $certurl = (new \moodle_url(
            '/local/courses/certificate.php',
            ['courseid' => $courseid]
        ))->out(false);

        return [
            'success'      => true,
            'coursename'   => $course->fullname,
            'username'     => fullname($USER),
            'firstname'    => $USER->firstname,
            'finalscore'   => $finalscore,
            'quizaccuracy' => $quizaccuracy,
            'certurl'      => $certurl,
        ];
    }

    public static function get_completion_popup_returns() {
        return new \external_single_structure([
            'success'      => new \external_value(PARAM_BOOL, 'Success'),
            'coursename'   => new \external_value(PARAM_TEXT, 'Course name'),
            'username'     => new \external_value(PARAM_TEXT, 'Full name'),
            'firstname'    => new \external_value(PARAM_TEXT, 'First name'),
            'finalscore'   => new \external_value(PARAM_INT,  'Final score %'),
            'quizaccuracy' => new \external_value(PARAM_INT,  'Quiz accuracy %'),
            'certurl'      => new \external_value(PARAM_URL,  'Certificate URL'),
        ]);
    }

    // ── get_improvement_suggestions ──────────────────────────────
    public static function get_improvement_suggestions_parameters() {
        return new \external_function_parameters([
            'courseid' => new \external_value(PARAM_INT, 'Course ID'),
        ]);
    }

    public static function get_improvement_suggestions(int $courseid): array {
        global $USER;
        self::validate_parameters(
            self::get_improvement_suggestions_parameters(),
            ['courseid' => $courseid]
        );
        $helper      = new course_helper();
        $suggestions = $helper->get_improvement_suggestions($USER->id, $courseid);
        return ['suggestions' => json_encode($suggestions)];
    }

    public static function get_improvement_suggestions_returns() {
        return new \external_single_structure([
            'suggestions' => new \external_value(PARAM_RAW, 'JSON suggestions'),
        ]);
    }
}
