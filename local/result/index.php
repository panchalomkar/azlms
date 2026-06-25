<?php
require_once('../../config.php');
require_once($CFG->dirroot . '/local/result/classes/result_helper.php');
require_login();

global $DB, $USER, $PAGE, $OUTPUT, $CFG;

$PAGE->set_url(new moodle_url('/local/result/index.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('result', 'local_result'));
$PAGE->set_pagelayout('base');

$PAGE->requires->css(new moodle_url(
    'https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css'));

$isadmin   = is_siteadmin();
$ismanager = user_has_role_assignment($USER->id, 9);

$helper = new local_result\result_helper();
$users  = $helper->get_user_list($USER->id, $isadmin, $ismanager);

$selecteduserid = optional_param('userid', $USER->id, PARAM_INT);
if (!$isadmin && $ismanager && !array_key_exists($selecteduserid, $users)) {
    $selecteduserid = array_key_first($users);
}
if (!$isadmin && !$ismanager) {
    $selecteduserid = $USER->id;
}

// ── Build user selector ──────────────────────────────────────────
$user_options = [];
foreach ($users as $uid => $name) {
    $user_options[] = [
        'value'    => $uid,
        'label'    => $name,
        'selected' => $uid == $selecteduserid,
    ];
}

// ── Fetch data ───────────────────────────────────────────────────
$gpa_data  = $helper->get_gpa($selecteduserid);
$att_data  = $helper->get_attendance($selecteduserid);
$ext_data  = $helper->get_externship($selecteduserid);

// ── Pagination for timesheet ─────────────────────────────────────
$perpage      = 6;
$page         = optional_param('tpage', 0, PARAM_INT);
$all_ts       = $ext_data['timesheets'];
$total_ts     = count($all_ts);
$ts_slice     = array_slice($all_ts, $page * $perpage, $perpage);
$total_pages  = max(1, (int)ceil($total_ts / $perpage));

// Build page links
$page_links = [];
for ($i = 0; $i < $total_pages; $i++) {
    $page_links[] = [
        'num'    => $i + 1,
        'offset' => $i,
        'active' => $i === $page,
        'url'    => (new moodle_url('/local/result/index.php',
                        ['userid' => $selecteduserid, 'tpage' => $i]))->out(false),
    ];
}

// ── Check if first site exists for modal ────────────────────────
$sites      = $DB->get_records('externship_sites', ['userid' => $selecteduserid]);
$hasSite    = !empty($sites);
$firstSiteId = $hasSite ? array_values($sites)[0]->id : 0;

// ── Template context ─────────────────────────────────────────────
$ctx = [
    // Meta
    'wwwroot'         => $CFG->wwwroot,
    'sesskey'         => sesskey(),
    'isadmin'         => $isadmin,
    'ismanager'       => $isadmin || $ismanager,
    'show_selector'   => $isadmin || $ismanager,
    'selected_userid' => $selecteduserid,
    'user_options'    => $user_options,

    // GPA card
    'has_gpa'         => $gpa_data['has_data'],
    'gpa_display'     => $gpa_data['gpa_display'],
    'gpa_percent'     => $gpa_data['percent'],
    'gpa_circle_circ' => round(2 * M_PI * 54, 2),
    'gpa_circle_offset'=> round(2 * M_PI * 54 * (1 - $gpa_data['percent']/100), 2),
    'semesters'       => $gpa_data['semesters'],
    'has_semesters'   => !empty($gpa_data['semesters']),

    // Attendance card
    'has_attendance'  => $att_data['has_data'],
    'att_display'     => $att_data['display'],
    'att_percent'     => $att_data['percent'],

    // Externship
    'has_sites'       => $ext_data['has_sites'],
    'sites'           => $ext_data['sites'],
    'has_timesheets'  => !empty($ts_slice),
    'timesheets'      => $ts_slice,
    'total_required'  => $ext_data['total_required'],
    'approved_hrs'    => $ext_data['approved'],
    'pending_hrs'     => $ext_data['pending'],
    'ext_percent'     => $ext_data['percent'],
    'greeting'        => $ext_data['greeting'],
    'site_count'      => $ext_data['site_count'],
    'ext_circ'        => $ext_data['svg_circ'],
    'ext_offset'      => $ext_data['svg_offset'],
    // Donut chart
    'donut_circ'      => $ext_data['circ'],
    'donut_approved'  => $ext_data['approved_dash'],
    'donut_pending'   => $ext_data['pending_dash'],

    // Pagination
    'ts_from'         => $total_ts > 0 ? ($page * $perpage + 1) : 0,
    'ts_to'           => min(($page + 1) * $perpage, $total_ts),
    'ts_total'        => $total_ts,
    'page_links'      => $page_links,
    'has_prev'        => $page > 0,
    'has_next'        => $page < $total_pages - 1,
    'prev_url'        => (new moodle_url('/local/result/index.php',
                            ['userid'=>$selecteduserid,'tpage'=>max(0,$page-1)]))->out(false),
    'next_url'        => (new moodle_url('/local/result/index.php',
                            ['userid'=>$selecteduserid,'tpage'=>min($total_pages-1,$page+1)]))->out(false),
    'has_site_modal'  => $hasSite,
    'first_site_id'   => $firstSiteId,
    'pdf_url'         => (new moodle_url('/local/result/pdf.php',
                            ['userid'=>$selecteduserid]))->out(false),
];

echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_result/result', $ctx);
echo $OUTPUT->footer();
