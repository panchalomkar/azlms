<?php
require_once("../config.php");
require_once($CFG->dirroot . '/user/profile/lib.php');
echo $OUTPUT->header();
?>
<!DOCTYPE html>
  <title>Arizona Transcript Template</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 40px;
    }
    h1 {
      text-align: center;
      text-transform: uppercase;
    }
    .section {
      margin-bottom: 20px;
    }
    .field {
      margin: 5px 0;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    th, td {
      border: 1px solid #000;
      padding: 5px;
      text-align: left;
    }
    .signature {
      margin-top: 40px;
    }
  </style>
<?php
profile_load_custom_fields($USER);
$name=$USER->firstname . ' ' . $USER->lastname;
$start=userdate($USER->timecreated);
$ssn = $USER->profile_field_ssn ?? '';
$dob = $USER->profile_field_dob ?? '';
$prepared = $USER->profile_field_prepared ?? '';

?>
  <h1>Arizona Transcript Template</h1>

  <div class="section" style="text-align: center;">
    <div><strong>(Institution Name)</strong></div>
    <div><strong>(Institution Address)</strong></div>
  </div>

  <div class="section">
  <div class="row">
    <div class="col-md-8"><div class="field">Student Name: <?=$name?></div></div>
    <div class="col-md-4"><div class="field">Date Prepared: <?=$prepared?></div></div>
	</div>
	<div class="row">
     <div class="col-md-4"> <div class="field">Start Date: <?=$start?> </div></div>
	 <div class="col-md-4"> <div class="field">Exit/Graduation Date: ______________</div></div>
    <div class="col-md-4"><div class="field">Last 4 of SSN: <?=$prepared?></div></div>
	</div>
	<div class="row">
    <div class="col-md-3"><div class="field">ID#: ________________</div></div>
	<div class="col-md-3"><div class="field"> DOB: __________</div></div>
    <div class="col-md-6"><div class="field">Program of Study: ____________________________________</div></div>
	</div><div class="row">
    <div class="col-md-6"><div class="field">Current Status: ___________________________</div></div>
    <div class="col-md-6"><div class="field">Date of Current Status: ___________________________</div></div>
	</div>
  </div>

  <div class="section">
    <strong>Term:</strong>
    <table>
      <thead>
        <tr>
          <th>Course #</th>
          <th>Course Title</th>
          <th>Term Start Date</th>
          <th>Credit/Clock Hours Attempted</th>
          <th>Term End Date</th>
          <th>Credit/Clock Hours Earned</th>
          <th>Grade (Letter or P/F)</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
        </tr>
		<tr>
          <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
        </tr>
		<tr>
          <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
        </tr>
		<tr>
          <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
        </tr>
		<tr>
			<td colspan="2"> <div class="field">Term GPA: __________</div></td><td colspan="5"><div class="field">Cum GPA: __________</div></td>
		</tr>
        <!-- Additional rows as needed -->
      </tbody>
    </table>
  </div>
<div class="row"> <div class="col-md-12">
  <div class="section">
    <div class="field">Degree/Certification Earned: _____________________________________________</div>
  </div></div>
</div>
<div class="row"> <div class="col-md-3">
  <div class="section">
    <div class="field">Notes/Comments:</div>
    <div class="field">______________________________________________________________________</div>
    <div class="field">______________________________________________________________________</div>
    <div class="field">______________________________________________________________________</div>
  </div>
</div></div>
  <div class="section">
    <div class="field">List of honors or certificates received:</div>
  </div>
<table>
<tr><td></td></tr>
<tr><td></td></tr>
<tr><td></td></tr>
<tr><td></td></tr>
<tr><td></td></tr>
</table><div class="row"> <div class="col-md-12">
  <div class="section">
    <div class="field">License Requirements for graduates of this program: 
      <input type="checkbox"> YES 
      <input type="checkbox"> NO
    </div></div>
	<div class="row"> <div class="col-md-6">
    <div class="field">
      If Yes, Met <input type="checkbox"> Not Met <input type="checkbox">
    </div></div><div class="col-md-6">
	<div class="field">Certified by: ___________________________________</div>
  </div></div>
	
  <div class="section signature"><div class="row"><div class="col-md-6"></div>
  <div class="col-md-6">
    <div class="field">Signature: ____________________________________</div>
	</div></div>
  </div>

  <div class="section signature"><div class="row">
   <div class="col-md-6"> <div class="field">Transcript /Grade Record prepared and certified by:</div></div>
    <div class="col-md-3"><div class="field">Name: _______________________</div></div><div class="col-md-3"><div class="field"> Title: ________________</div></div>
	</div><div class="row"><div class="col-md-6"></div><div class="col-md-6">
    <div class="field">Signature: ____________________________________</div>
  </div></div></div>
<?php

echo $OUTPUT->footer();
?>