<?php
require_once('../../config.php');
require_once($CFG->dirroot . '/local/result/classes/result_helper.php');
require_login();

global $DB, $USER, $PAGE, $CFG;

$userid    = optional_param('userid', $USER->id, PARAM_INT);
$isadmin   = is_siteadmin();
$ismanager = user_has_role_assignment($USER->id, 9);

// Permission check
if (!$isadmin && !$ismanager && $userid !== $USER->id) {
    throw new moodle_exception('nopermissions', 'error');
}

$helper  = new local_result\result_helper();
$gpa     = $helper->get_gpa($userid);
$att     = $helper->get_attendance($userid);
$ext     = $helper->get_externship($userid);
$student = \core_user::get_user($userid);

// Output PDF as HTML (print-styled page)
// For real PDF use mPDF or tcpdf library
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Result - <?= fullname($student) ?></title>
<style>
  body { font-family: Arial, sans-serif; padding: 32px; color: #333; }
  h1   { font-size: 22px; color: #1a2a4a; margin-bottom: 4px; }
  .sub { font-size: 13px; color: #888; margin-bottom: 24px; }
  .section { margin-bottom: 20px; }
  .section h2 { font-size: 15px; font-weight: 700; color: #1a2a4a;
                border-bottom: 2px solid #eef0f4; padding-bottom: 6px; margin-bottom: 12px; }
  .row { display: flex; gap: 32px; flex-wrap: wrap; margin-bottom: 8px; }
  .stat { flex: 1; min-width: 120px; }
  .stat .label { font-size: 11px; color: #888; }
  .stat .value { font-size: 22px; font-weight: 700; color: #1a2a4a; }
  table { width: 100%; border-collapse: collapse; font-size: 13px; }
  th { background: #f4f5f7; padding: 8px 12px; text-align: left; font-weight: 600; }
  td { padding: 8px 12px; border-bottom: 1px solid #eef0f4; }
  .badge { display: inline-block; padding: 2px 8px; border-radius: 50px; font-size: 11px; }
  .approved { background: #eafaf3; color: #1faa6b; }
  .pending  { background: #fff4e0; color: #e07b00; }
  .rejected { background: #fff0f0; color: #e03c3c; }
  @media print {
    body { padding: 0; }
    .no-print { display: none; }
  }
</style>
</head>
<body>
<div class="no-print" style="margin-bottom:20px;">
  <button onclick="window.print()"
    style="background:#1a2a4a;color:#fff;border:none;border-radius:8px;padding:10px 24px;cursor:pointer;font-size:14px;">
    🖨 Print / Save as PDF
  </button>
</div>

<h1>Result Report</h1>
<div class="sub">Student: <strong><?= fullname($student) ?></strong> &nbsp;|&nbsp; Generated: <?= date('d M Y H:i') ?></div>

<!-- GPA -->
<div class="section">
  <h2>GPA / Score</h2>
  <?php if ($gpa['has_data']): ?>
  <div class="row">
    <div class="stat">
      <div class="label">Overall GPA</div>
      <div class="value"><?= $gpa['gpa_display'] ?><span style="font-size:14px;color:#888;">/4.0</span></div>
    </div>
    <div class="stat">
      <div class="label">Score %</div>
      <div class="value"><?= $gpa['percent'] ?>%</div>
    </div>
  </div>
  <div class="row">
    <?php foreach ($gpa['semesters'] as $i => $s): ?>
    <div class="stat">
      <div class="label"><?= $s['label'] ?></div>
      <div class="value" style="font-size:18px;"><?= $s['gpa'] ?? '-' ?></div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <p style="color:#aaa;">No grade data available.</p>
  <?php endif; ?>
</div>

<!-- Attendance -->
<div class="section">
  <h2>Attendance Overview</h2>
  <?php if ($att['has_data']): ?>
  <div class="stat">
    <div class="label">Total Attendance</div>
    <div class="value" style="color:#1faa6b;"><?= $att['display'] ?></div>
  </div>
  <?php else: ?>
  <p style="color:#aaa;">No attendance data available.</p>
  <?php endif; ?>
</div>

<!-- Externship -->
<div class="section">
  <h2>Externship Details</h2>
  <p>Total Required: <strong><?= $ext['total_required'] ?> hrs</strong>
     &nbsp;|&nbsp; Approved: <strong><?= $ext['approved'] ?> hrs</strong>
     &nbsp;|&nbsp; Pending: <strong><?= $ext['pending'] ?> hrs</strong>
     &nbsp;|&nbsp; Progress: <strong><?= $ext['percent'] ?>%</strong></p>

  <?php if (!empty($ext['sites'])): ?>
    <?php foreach ($ext['sites'] as $s): ?>
    <p><strong><?= $s['companyname'] ?></strong> &mdash;
       <?= $s['address'] ?> &mdash; <?= $s['phone'] ?><br>
       Supervisor: <?= $s['supervisor'] ?> &mdash; Start: <?= $s['startdate'] ?></p>
    <?php endforeach; ?>
  <?php else: ?>
  <p style="color:#aaa;">No externship site assigned.</p>
  <?php endif; ?>
</div>

<!-- Timesheet -->
<div class="section">
  <h2>Timesheet Details</h2>
  <?php if (!empty($ext['timesheets'])): ?>
  <table>
    <thead>
      <tr>
        <th>Date</th><th>Start</th><th>End</th>
        <th>Attend Hrs</th><th>Sched Hrs</th><th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($ext['timesheets'] as $t): ?>
      <tr>
        <td><?= $t['externdate'] ?></td>
        <td><?= $t['starttime'] ?></td>
        <td><?= $t['endtime']   ?></td>
        <td><?= $t['attendhrs'] ?></td>
        <td><?= $t['schedhrs']  ?></td>
        <td><span class="badge <?= strtolower($t['status']) ?>"><?= $t['status'] ?></span></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php else: ?>
  <p style="color:#aaa;">No timesheet records.</p>
  <?php endif; ?>
</div>

</body>
</html>
<?php
exit;
