<?php
require_once('config.php');
require_login();

$context = context_system::instance();
require_capability('moodle/site:viewreports', $context);

$PAGE->set_url(new moodle_url('/request_report.php'));
$PAGE->set_context($context);
$PAGE->set_title('Contact Form Report');
$PAGE->set_heading('Contact Form Report');

$perpage = 10; // records per page
$page = optional_param('page', 0, PARAM_INT);
$search = optional_param('search', '', PARAM_TEXT);

$params = [];
$sqlwhere = '';

/*$sql = "SELECT * FROM {contact_form}";
$records = $DB->get_records_sql($sql);
echo"<pre>";
print_r($records);die;*/


if (!empty($search)) {
    $sqlwhere = "WHERE fullname LIKE :search1 OR email LIKE :search2";
    $params['search1'] = "%{$search}%";
    $params['search2'] = "%{$search}%";
}

// count total records
$countsql = "SELECT COUNT(*) FROM {contact_form} {$sqlwhere}";
$totalcount = $DB->count_records_sql($countsql, $params);

// fetch records
$sql = "SELECT * FROM {contact_form} {$sqlwhere} ORDER BY timecreated DESC";
$records = $DB->get_records_sql($sql, $params, $page * $perpage, $perpage);

echo $OUTPUT->header();

// search form
echo '<form method="get" action="" style="margin-bottom: 1em;">';
echo '<input type="text" name="search" value="'.s($search).'" placeholder="Search by name or email">';
echo '<button type="submit">Search</button>';
echo '</form>';

if ($records) {
    echo html_writer::start_tag('table', ['class' => 'generaltable']);
    echo html_writer::start_tag('tr');
    echo html_writer::tag('th', 'Full Name');
    echo html_writer::tag('th', 'Phone');
    echo html_writer::tag('th', 'Email');
    echo html_writer::tag('th', 'Subject');
    echo html_writer::tag('th', 'Submitted At');
    echo html_writer::end_tag('tr');

    foreach ($records as $record) {
        echo html_writer::start_tag('tr');
        echo html_writer::tag('td', s($record->fullname));
        echo html_writer::tag('td', s($record->phone));
        echo html_writer::tag('td', s($record->email));
        echo html_writer::tag('td', s($record->subject));
        echo html_writer::tag('td', userdate($record->timecreated));
        echo html_writer::end_tag('tr');
    }

    echo html_writer::end_tag('table');
} else {
    echo $OUTPUT->notification('No records found.', 'notifyproblem');
}

// paging bar
$baseurl = new moodle_url('/request_report.php', ['search' => $search]);
echo $OUTPUT->paging_bar($totalcount, $page, $perpage, $baseurl);

echo $OUTPUT->footer();
