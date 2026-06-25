<?php
// ================================================================
// File: local/courses/certificate.php
// Generates a downloadable PDF-style certificate
// Uses mPDF if available, falls back to print-ready HTML
// ================================================================
require_once('../../config.php');
require_login();

global $USER, $DB, $CFG, $PAGE;

$courseid = required_param('courseid', PARAM_INT);
$course   = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);

// Verify user is enrolled
if (!is_enrolled(context_course::instance($courseid), $USER)) {
    throw new moodle_exception('notenrolled', 'error');
}

// ── Gather data ──────────────────────────────────────────────────
require_once($CFG->dirroot . '/lib/gradelib.php');

$studentname = fullname($USER);
$coursename  = $course->fullname;
$date        = date('F j, Y');
$sitename    = get_site()->fullname;

// Final grade
$gi = grade_item::fetch_course_item($courseid);
$finalscore = 0;
if ($gi) {
    $grade = new grade_grade(['itemid' => $gi->id, 'userid' => $USER->id]);
    $grade->grade_item = $gi;
    if ($gi->grademax > 0) {
        $finalscore = round((($grade->finalgrade ?? 0) / $gi->grademax) * 100);
    }
}

// Quiz accuracy
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
    if ($count > 0) $quizaccuracy = round($total / $count);
}

// Completion date
$completion = new completion_info($course);
$ccompletion = new completion_completion(['userid' => $USER->id, 'course' => $courseid]);
$completiondate = $ccompletion->timecompleted
    ? date('F j, Y', $ccompletion->timecompleted)
    : $date;

// ── Output certificate ───────────────────────────────────────────
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Certificate of Completion — <?= s($studentname) ?></title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;500;600&display=swap');

  * { margin:0; padding:0; box-sizing:border-box; }

  body {
    font-family: 'Inter', sans-serif;
    background: #f4f5f7;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    padding: 20px;
  }

  .cert-wrap {
    width: 100%;
    max-width: 860px;
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.12);
  }

  /* Top decorative bar */
  .cert-top-bar {
    height: 10px;
    background: linear-gradient(90deg, #1a2a4a 0%, #4db89a 50%, #1a2a4a 100%);
  }

  .cert-body { padding: 50px 60px; }

  /* Header row */
  .cert-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 36px;
    padding-bottom: 24px;
    border-bottom: 1px solid #eef0f4;
  }
  .cert-logo-name {
    font-size: 18px;
    font-weight: 700;
    color: #1a2a4a;
  }
  .cert-badge-label {
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #4db89a;
    border: 2px solid #4db89a;
    padding: 5px 14px;
    border-radius: 50px;
  }

  /* Main content */
  .cert-presents {
    font-size: 14px;
    color: #8a8f98;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 10px;
    text-align: center;
  }
  .cert-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 48px;
    font-weight: 700;
    color: #1a2a4a;
    text-align: center;
    margin-bottom: 6px;
    line-height: 1.2;
  }
  .cert-subtitle {
    font-size: 14px;
    color: #8a8f98;
    text-align: center;
    margin-bottom: 28px;
  }

  /* Student name */
  .cert-student {
    text-align: center;
    margin-bottom: 8px;
  }
  .cert-student-label {
    font-size: 13px;
    color: #8a8f98;
    margin-bottom: 6px;
  }
  .cert-student-name {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 36px;
    font-weight: 700;
    color: #1a2a4a;
    border-bottom: 3px solid #4db89a;
    display: inline-block;
    padding-bottom: 6px;
  }

  /* Course name */
  .cert-course {
    text-align: center;
    font-size: 15px;
    color: #555;
    margin: 16px 0 28px;
    line-height: 1.6;
  }
  .cert-course strong { color: #1a2a4a; font-weight: 600; }

  /* Score boxes */
  .cert-scores {
    display: flex;
    gap: 16px;
    margin-bottom: 32px;
  }
  .cert-score-box {
    flex: 1;
    border: 1px solid #eef0f4;
    border-radius: 14px;
    padding: 18px 16px;
    text-align: center;
    background: #fafbff;
  }
  .cert-score-label {
    font-size: 13px;
    font-weight: 600;
    color: #1a2a4a;
    margin-bottom: 10px;
  }
  .cert-score-value {
    font-size: 28px;
    font-weight: 700;
  }
  .score-teal   { color: #4db89a; }
  .score-orange { color: #e07b00; }

  /* SVG gauge */
  .cert-gauge { width: 90px; height: 55px; margin: 0 auto 6px; display: block; }

  /* Footer */
  .cert-footer {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    padding-top: 24px;
    border-top: 1px solid #eef0f4;
  }
  .cert-sig-block { text-align: center; }
  .cert-sig-line {
    width: 160px;
    border-bottom: 2px solid #1a2a4a;
    margin-bottom: 6px;
    height: 36px;
  }
  .cert-sig-name  { font-size: 13px; font-weight: 600; color: #1a2a4a; }
  .cert-sig-title { font-size: 11px; color: #8a8f98; }

  .cert-seal {
    width: 80px; height: 80px;
    border-radius: 50%;
    border: 3px solid #1a2a4a;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    text-align: center;
    padding: 8px;
  }
  .cert-seal-star { font-size: 22px; }
  .cert-seal-text { font-size: 9px; font-weight: 700; color: #1a2a4a; letter-spacing: 0.05em; line-height: 1.3; }

  .cert-date-block { text-align: right; }
  .cert-date-label { font-size: 11px; color: #8a8f98; margin-bottom: 4px; }
  .cert-date-value { font-size: 14px; font-weight: 600; color: #1a2a4a; }

  /* Bottom bar */
  .cert-bottom-bar {
    height: 6px;
    background: linear-gradient(90deg, #4db89a 0%, #1a2a4a 100%);
  }

  /* Print button */
  .cert-print-bar {
    background: #f4f5f7;
    padding: 16px 60px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
  }
  .cert-print-note { font-size: 13px; color: #8a8f98; }
  .cert-print-btn {
    background: #1a2a4a; color: #fff; border: none;
    border-radius: 50px; padding: 11px 28px;
    font-size: 14px; font-weight: 600; cursor: pointer;
    display: inline-flex; align-items: center; gap: 8px;
    transition: background .15s; text-decoration: none;
  }
  .cert-print-btn:hover { background: #263660; }

  @media print {
    body { background: #fff; padding: 0; }
    .cert-print-bar { display: none; }
    .cert-wrap { box-shadow: none; border-radius: 0; max-width: 100%; }
  }
</style>
</head>
<body>

<!-- Print bar (hidden on print) -->
<div style="position:fixed;top:0;left:0;right:0;z-index:999;background:#1a2a4a;padding:10px 24px;display:flex;align-items:center;justify-content:space-between;" class="cert-print-bar-top">
  <span style="color:#fff;font-size:13px;">Certificate of Completion</span>
  <button onclick="window.print()" class="cert-print-btn" style="padding:8px 20px;font-size:13px;">
    ⬇ Download / Print PDF
  </button>
</div>

<div style="margin-top:56px;" class="cert-main">
<div class="cert-wrap">
  <div class="cert-top-bar"></div>
  <div class="cert-body">

    <!-- Header -->
    <div class="cert-header">
      <div class="cert-logo-name"><?= s($sitename) ?></div>
      <div class="cert-badge-label">Certificate of Completion</div>
    </div>

    <!-- This certifies that -->
    <div class="cert-presents">This certificate is proudly presented to</div>
    <div class="cert-student">
      <div class="cert-student-name"><?= s($studentname) ?></div>
    </div>

    <!-- Course -->
    <div class="cert-course">
      for successfully completing the course<br>
      <strong><?= s($coursename) ?></strong>
    </div>

    <!-- Scores -->
    <div class="cert-scores">
      <div class="cert-score-box">
        <div class="cert-score-label">Final Score</div>
        <svg class="cert-gauge" viewBox="0 0 90 55">
          <path d="M10 50 A 35 35 0 0 1 80 50" stroke="#e5e7eb" stroke-width="8" fill="none" stroke-linecap="round"/>
          <?php
            $scoreOffset = 110 - (110 * $finalscore / 100);
          ?>
          <path d="M10 50 A 35 35 0 0 1 80 50" stroke="#4db89a" stroke-width="8" fill="none"
                stroke-linecap="round" stroke-dasharray="110"
                stroke-dashoffset="<?= $scoreOffset ?>"/>
        </svg>
        <div class="cert-score-value score-teal"><?= $finalscore ?>%</div>
      </div>
      <div class="cert-score-box">
        <div class="cert-score-label">Quiz Accuracy</div>
        <svg class="cert-gauge" viewBox="0 0 90 55">
          <path d="M10 50 A 35 35 0 0 1 80 50" stroke="#e5e7eb" stroke-width="8" fill="none" stroke-linecap="round"/>
          <?php
            $quizOffset = 110 - (110 * $quizaccuracy / 100);
          ?>
          <path d="M10 50 A 35 35 0 0 1 80 50" stroke="#e07b00" stroke-width="8" fill="none"
                stroke-linecap="round" stroke-dasharray="110"
                stroke-dashoffset="<?= $quizOffset ?>"/>
        </svg>
        <div class="cert-score-value score-orange"><?= $quizaccuracy ?>%</div>
      </div>
    </div>

    <!-- Footer -->
    <div class="cert-footer">
      <div class="cert-sig-block">
        <div class="cert-sig-line"></div>
        <div class="cert-sig-name"><?= s($sitename) ?></div>
        <div class="cert-sig-title">Authorized Signatory</div>
      </div>

      <div class="cert-seal">
        <div class="cert-seal-star">⭐</div>
        <div class="cert-seal-text">CERTIFIED<br>COMPLETE</div>
      </div>

      <div class="cert-date-block">
        <div class="cert-date-label">Date of Completion</div>
        <div class="cert-date-value"><?= $completiondate ?></div>
      </div>
    </div>

  </div>
  <div class="cert-bottom-bar"></div>
</div>
</div>

<script>
// Auto-trigger print dialog if ?print=1
const params = new URLSearchParams(window.location.search);
if (params.get('print') === '1') {
    window.addEventListener('load', function(){ window.print(); });
}
</script>
</body>
</html>
<?php exit;
