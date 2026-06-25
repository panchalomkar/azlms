<?php
require_once('../config.php');
require_once($CFG->dirroot.'/user/profile/lib.php');

require_login(); // ✅ Make sure user is logged in

$context = context_system::instance(); // Or course context if needed
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/myscript.php')); // Set correct script URL
$PAGE->set_title("Custom Profile Viewer");
$PAGE->set_heading("User Profile Info");
echo $OUTPUT->header();
global $USER, $DB;

// Get full user object
$user = core_user::get_user(8);

// Fetch custom fields
$fields = $DB->get_records_sql("
    SELECT f.shortname, d.data
    FROM {user_info_field} f
    JOIN {user_info_data} d ON d.fieldid = f.id
    WHERE d.userid = ?
", [$user->id]);

// Map fields
$profilefields = [];
foreach ($fields as $field) {
    $profilefields[$field->shortname] = $field->data;
}

// Core user info
$name = $user->firstname . ' ' . $user->lastname;
$start = userdate($user->timecreated);  // Moodle's helper

// Safe custom field access
$ssn = $profilefields['ssn'] ?? '';
$dob = $profilefields['dob'] ?? '';
$prepared = $profilefields['prepared'] ?? '';
$pofstudy  = $profilefields['pofstudy'] ?? '';
$emid  = $profilefields['emid'] ?? '';
$current_status = $profilefields['current_status'] ?? '';
$dcs = $profilefields['dcs'] ?? '';
$notes = $profilefields['notes'] ?? '';



// Last access
$lastaccess = $user->lastaccess ?? 0;
$eedt = $lastaccess ? userdate($lastaccess, '%d-%m-%Y') : 'Never accessed';

// Convert timestamp-like custom field safely
if (is_numeric($current_status) && (int)$current_status > 1000000000) {
    $cdt = date('d-m-Y', (int)$current_status);
} else {
    $cdt = '';
}

$sql="SELECT
  c.fullname AS course_name,
  c.shortname AS shortname_name,
  FROM_UNIXTIME(ue.timestart, '%d-%m-%Y') AS enrol_date,
  ROUND(gi.grademax, 2) AS total_marks,
  FROM_UNIXTIME(ue.timecreated, '%d-%m-%Y') AS enrol_created_time,
  FROM_UNIXTIME(ul.timeaccess, '%d-%m-%Y %H:%i:%s') AS last_access
FROM mdl_user_enrolments ue
JOIN mdl_enrol e ON e.id = ue.enrolid
JOIN mdl_course c ON c.id = e.courseid
LEFT JOIN mdl_user_lastaccess ul ON ul.courseid = c.id AND ul.userid = ue.userid
LEFT JOIN mdl_grade_items gi ON gi.courseid = c.id AND gi.itemtype = 'course'
WHERE ue.userid = 8;
";

//$userid = 8; // target user ID
$records = $DB->get_records_sql($sql);



?>

<!DOCTYPE html>
<html>
<head>
  <title>Arizona Transcript Template</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
      font-size: 11px;
    }
    h1 {
      text-align: center;
      text-transform: uppercase;
      font-size: 16px;
    }
    .section {
      margin-bottom: 10px;
    }
    .field {
      margin: 2px 0;
    }
    table {
      width: 100%;
       table-layout: fixed; /* Forces columns to fit */
    word-wrap: break-word;
      border-collapse: collapse;
      margin-top: 5px;
      font-size: 10px;
    }
    th, td {
      border: 1px solid #000;
      padding: 3px;
      text-align: left;
    overflow-wrap: break-word;
    }
    .signature {
      margin-top: 20px;
    }
    .row {
      display: flex;
      flex-wrap: wrap;
    }
    .col-md-3 { width: 25%; }
    .col-md-4 { width: 33.33%; }
    .col-md-6 { width: 50%; }
    .col-md-8 { width: 66.66%; }
    .col-md-12 { width: 100%; }
    @media print {
      body {
        zoom: 80%;
      }
    }
    button {
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

<button onclick="downloadPDF()">Download PDF</button>

<div id="transcript-content">
  <h1>Arizona Transcript Template</h1>

  <div class="section" style="text-align: center;">
    <div><strong>(Arizona School of Medical Assistant, LLC)</strong></div>
    <div><strong>(13943 N. 91st Ave., Suite A-101 Peoria, AZ 85381)</strong></div>
  </div>

  <div class="section">
    <div class="row">
      <div class="col-md-9"><div class="field">Student Name: <?= $name ?></div></div>
      <div class="col-md-3"><div class="field">Date Prepared: <?= $prepared ?></div></div>
    </div>
    <div class="row">
      <div class="col-md-6"><div class="field">Start Date: <?= $start ?></div></div>
      <div class="col-md-3"><div class="field">Exit/Graduation Date: <?=$eedt?></div></div>
      <div class="col-md-3"><div class="field">Last 4 of SSN: <?= substr($ssn, -4) ?></div></div>
    </div>
    <div class="row">
      <div class="col-md-6"><div class="field">ID#:<?=$emid?></div></div>
      <div class="col-md-3"><div class="field">DOB: <?= $dob ?></div></div>
      <div class="col-md-3"><div class="field">Program of Study: <?=$eedt?></div></div>
    </div>
    <div class="row">
      <div class="col-md-9"><div class="field">Current Status: <?=$cdt?></div></div>
      <div class="col-md-3"><div class="field">Date of Current Status: <?=$dcs?></div></div>
    </div>
  </div>

  <div class="section">
    <strong>Term:</strong>
    
    <table>
      <thead>
        <tr>
          <th style="width: 186px;">Course #</th>
          <th style="width: 186px;">Course Title</th>
          <th style="width: 81px;">Term Start Date</th>
          <th style="width: 81px;">Credit/Clock Hours Attempted</th>
          <th style="    width: 90px;">Term End Date</th>
          <th style="width: 81px;">Credit/Clock Hours Earned</th>
          <th style="width: 81px;">Grade (Letter or P/F)</th>
        </tr>
      </thead>
      <tbody>
	  <?php
	  if(!empty($records)){
		  foreach($records as $recod){
		  echo'<tr><td>'.$recod->course_name.'</td>
		  <td>'.$recod->shortname_name.'</td>
		  <td>'.$recod->enrol_date.'</td>
		  <td></td>
		  <td>'.$recod->enrol_created_time.'</td>
		  <td></td>
		  <td>'.$recod->total_marks.'</td></tr>';
		  }
	  }
	  ?>
        
        
        <tr><td colspan="2"><div class="field">Term GPA: __________</div></td><td colspan="5"><div class="field">Cum GPA: __________</div></td></tr>
      </tbody>
    </table>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="section">
        <div class="field">Degree/Certification Earned: _____________________________________________</div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-3">
      <div class="section">
        <!-- <div class="field">Notes/Comments:</div>
        <div class="field"><?=$notes?></div> -->
       
      </div>
    </div>
  </div>

  <div class="section">
    <div class="field">List of honors or certificates received:</div>
  </div>

  <table>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
  </table>

  <div class="row">
    <div class="col-md-12">
      <div class="section">
        <div class="field">License Requirements for graduates of this program:
          <input type="checkbox"> YES
          <input type="checkbox"> NO
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="field">
            If Yes, Met <input type="checkbox"> Not Met <input type="checkbox">
          </div>
        </div>
        <div class="col-md-6">
          <?php
           $admin = get_admin();
           $currentuser = $USER;
?>
          <div class="field">Certified by: <?php echo fullname($admin);
?></div>
        </div>
      </div>
    </div>
  </div>

  <div class="section signature">
    <div class="row">
      <div class="col-md-6"></div>
      <div class="col-md-6"><div class="field">Signature: <img style="width: 100px;height: 81px;" src="img/img.png" alt=""></div></div>
    </div>
  </div>

  <div class="section signature">
    <div class="row">
      <div class="col-md-6"><div class="field">Transcript /Grade Record prepared and certified by:</div></div>
      <div class="col-md-3"><div class="field">Name: <?php echo fullname($currentuser) ?></div></div>
      <!-- <div class="col-md-3"><div class="field">Title: ________________</div></div> -->
    </div>
    <div class="row">
      <div class="col-md-6"></div>
      <div class="col-md-6"><div class="field">Signature: ____________________________________</div></div>
    </div>
  </div>
</div>

<!-- Include html2pdf.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
function downloadPDF() {
    const element = document.getElementById('transcript-content');
    const opt = {
        margin: 0.3,
        filename: 'Transcript.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2, useCORS: true },
        jsPDF: { unit: 'in', format: 'letter', orientation: 'landscape' },
        pagebreak: { mode: ['css', 'legacy', 'avoid-all'] } // avoid breaking inside rows
    };
    html2pdf().set(opt).from(element).save();
}

</script>

</body>
</html>

<?php echo $OUTPUT->footer(); ?>
