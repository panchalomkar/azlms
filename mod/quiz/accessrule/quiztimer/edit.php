<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * @package    quizaccess_quiztimer
 * @copyright  2023 Proyecto UNIMOODLE
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../../config.php');
require_once($CFG->dirroot . '/mod/quiz/accessrule/quiztimer/lib.php');
require_once($CFG->libdir . '/tablelib.php');
require_once($CFG->dirroot . '/mod/quiz/locallib.php');
require_once($CFG->dirroot . '/question/editlib.php');

// ── FIX 1: correct use statements for Moodle 4.2+ ──────────────────────────
use quizaccess_quiztimer\quiz_options;
use mod_quiz\quiz_settings;
use mod_quiz\question\bank\custom_view;

$scrollpos = optional_param('scrollpos', '', PARAM_INT);

list($thispageurl, $contexts, $cmid, $cm, $quiz, $pagevars) =
        question_edit_setup('editq', '/mod/quiz/edit.php', true);

require_capability('quizaccess/quiztimer:manage', context_module::instance($PAGE->cm->id), $USER->id, true,
    $errormessage = 'nopermissions', $stringfile = '');
$defaultcategoryobj = question_make_default_categories($contexts->all());
$defaultcategory = $defaultcategoryobj->id . ',' . $defaultcategoryobj->contextid;

$quizhasattempts = quiz_has_attempts($quiz->id);

$thispageurl = new moodle_url('/mod/quiz/accessrule/quiztimer/edit.php', ['cmid' => $cmid]);
$PAGE->set_url($thispageurl);
$PAGE->set_secondary_active_tab("mod_quiz_edit");
$PAGE->navbar->add(get_string('pluginname', 'quizaccess_quiztimer'),
    new moodle_url('/mod/quiz/accessrule/quiztimer/edit.php', ['cmid' => $cmid]));

$course = $DB->get_record('course', ['id' => $quiz->course], '*', MUST_EXIST);
require_login($course);

$quizobj = new quiz_settings($quiz, $cm, $course);
$structure = $quizobj->get_structure();

require_capability('mod/quiz:manage', $contexts->lowest());

$selectedslots = [];
$params = (array) data_submitted();
foreach ($params as $key => $value) {
    if (preg_match('!^s([0-9]+)$!', $key, $matches)) {
        $selectedslots[] = $matches[1];
    }
}

$event = \mod_quiz\event\edit_page_viewed::create([
    'courseid' => $course->id,
    'context'  => $contexts->lowest(),
    'other'    => ['quizid' => $quiz->id],
]);
$event->trigger();

// ── Build $viewparams with guaranteed 'cat' key for custom_view ──────────────
$cat = optional_param('cat', '', PARAM_SEQUENCE);
if (empty($cat)) {
    $cat = $defaultcategory;  // e.g. "12,34" — id,contextid
}
$viewparams         = $pagevars;
$viewparams['cat']  = $cat;

// custom_view 5th arg = $params array (needs 'cat'/'filter' keys)
// 6th arg = $extraparams []
$questionbank = new custom_view($contexts, $thispageurl, $course, $cm, $viewparams, []);
// $questionbank->set_quiz_has_attempts($quizhasattempts);

$PAGE->set_pagelayout('incourse');
$PAGE->add_body_class('limitedwidth');
$PAGE->set_pagetype('mod-quiz-edit');

$output = $PAGE->get_renderer('quizaccess_quiztimer', 'edit');

$PAGE->set_title(get_string('editingquizx', 'quiz', format_string($quiz->name)));
$PAGE->set_heading($course->fullname);
$PAGE->activityheader->disable();
$PAGE->navbar->add(get_string('quiztime', 'quizaccess_quiztimer'));
echo $OUTPUT->header();

$quizeditconfig = new stdClass();
$quizeditconfig->url = $thispageurl->out(true, ['qbanktool' => '0']);
$quizeditconfig->dialoglisteners = [];
$numberoflisteners = $DB->get_field_sql("
    SELECT COALESCE(MAX(page), 1)
      FROM {quiz_slots}
     WHERE quizid = ?", [$quiz->id]);

for ($pageiter = 1; $pageiter <= $numberoflisteners; $pageiter++) {
    $quizeditconfig->dialoglisteners[] = 'addrandomdialoglaunch_' . $pageiter;
}

$PAGE->requires->data_for_js('quiz_edit_config', $quizeditconfig);
$PAGE->requires->js('/question/qengine.js');

$edittype = optional_param('edittype', null, PARAM_TEXT);
$quizopt = new quiz_options();
if ($edittype === null) {
    $edittype = $quizopt->get_quiz_option($quiz->id);
} else {
    $quizopt->set_quiz_option($quiz->id, $edittype);
}
$PAGE->requires->js_call_amd('quizaccess_quiztimer/time', 'init', ['edittype' => $edittype]);

echo html_writer::start_tag('div', ['class' => 'mod-quiz-edit-content']);
echo $output->edit_page($quizobj, $structure, $contexts, $thispageurl, $pagevars);
echo html_writer::end_tag('div');

echo $OUTPUT->footer();