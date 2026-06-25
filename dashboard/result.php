<?php
require_once('../config.php');
require_login();
global $DB, $USER, $PAGE, $OUTPUT;
$logouturl = new moodle_url('/login/logout.php', array('sesskey' => sesskey()));
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/dashboard/result.php'));
$PAGE->set_title("Externship Result");
$PAGE->set_heading("Externship Result");


$selecteduserid = optional_param('userid', $USER->id, PARAM_INT);


$isadmin = is_siteadmin();
$ismanager = user_has_role_assignment($USER->id, 9); // Manager roleid = 16


if ($isadmin) {
    // Admins see all users
    $users = $DB->get_records_sql_menu("
        SELECT id, CONCAT(firstname, ' ', lastname) AS fullname
        FROM {user}
        WHERE deleted = 0
        ORDER BY lastname ASC
    ");
} elseif ($ismanager) {
    // Managers see only their assigned users (based on custom field 'manager')
$manageremail = trim($USER->email); // use email instead of firstname

$users = $DB->get_records_sql_menu("
    SELECT u.id, CONCAT(u.firstname, ' ', u.lastname) AS fullname
    FROM {user} u
    JOIN {user_info_data} uid ON uid.userid = u.id
    JOIN {user_info_field} uif ON uif.id = uid.fieldid
    WHERE uif.shortname = 'manager'
      AND uid.data LIKE ?
      AND u.deleted = 0
    ORDER BY u.lastname ASC
", ['%' . $manageremail . '%']);


    // If manager has no users, show only themselves
    if (empty($users)) {
        $users = [$USER->id => fullname($USER)];
    }

    // ✅ Ensure selected user is one of manager’s users
    if (!array_key_exists($selecteduserid, $users)) {
        $selecteduserid = array_key_first($users);
    }
} else {
    // Regular user sees only themselves
    $users = [$USER->id => fullname($USER)];
    $selecteduserid = $USER->id;
}

// ✅ Fetch externship site(s)
$sites = $DB->get_records('externship_sites', ['userid' => $selecteduserid], 'startdate ASC');

// ✅ Fetch timesheet entries
$timesheets = $DB->get_records('externship_timesheet', ['userid' => $selecteduserid], 'externdate ASC');

// ✅ Calculate totals
$total_required = 99; // or fetch dynamically
$approved = $DB->get_field_sql("
    SELECT SUM(attendhrs) FROM {externship_timesheet}
    WHERE userid = ? AND status = 'Approved'
", [$selecteduserid]) ?: 0;

$pending = $DB->get_field_sql("
    SELECT SUM(attendhrs) FROM {externship_timesheet}
    WHERE userid = ? AND status = 'Pending'
", [$selecteduserid]) ?: 0;

// ✅ Completion percentage + greeting
$percentage = min(round(($approved / $total_required) * 100), 100);
if ($percentage >= 100) {
    $greeting = "Congratulations, you've completed your externship!";
} elseif ($percentage >= 75) {
    $greeting = "Great job, you’re almost done!";
} elseif ($percentage >= 50) {
    $greeting = "You're halfway there!";
} else {
    $greeting = "Keep going, you can do it!";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title> Result | Arizona School Medical Assistant</title>
    <link rel="icon" type="image/x-icon" href="assets/images/common/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/global.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
/* Circular progress */
.progress-circle {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  background: conic-gradient(
    #28a745 <?= $percentage ?>%, 
    #e9ecef <?= $percentage ?>%
  );
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 28px;
  font-weight: bold;
  color: #28a745;
  margin: auto;
}
.progress-circle-small {
  font-size: 18px;
  color: #333;
}
.asCircleBoxExternCont ul {
  list-style: none;
  padding: 0;
}
.asCircleBoxExternCont ul li {
  padding: 6px 0;
  border-bottom: 1px solid #e9ecef;
}
.greeting {
  text-align: center;
  font-weight: 500;
  color: #28a745;
  margin-top: 12px;
  font-size: 1rem;
}
</style>

</head>

<body>
<main class="asDashboardMain d-flex">
    <?php require_once('lefnav.php'); ?>
    <section class="flex-grow-1 DashboardHBFMain">
        <?php require_once('hederu.php'); ?>

        <div class="dashboardBody">

           <?php if ($isadmin || $ismanager) : ?>

    <!-- Admin user selector -->
    <div class="d-flex align-items-center mb-3 gap-2 col-md-4">
        <form method="get" class="flex-grow-1">
            <label for="userid" class="form-label">Select User:</label>
            <div class="input-group">
                <select name="userid" id="userid" class="form-select" onchange="this.form.submit()">
                    <?php foreach ($users as $uid => $fullname): ?>
                        <option value="<?= $uid ?>" <?= $uid == $selecteduserid ? 'selected' : '' ?>><?= $fullname ?></option>
                    <?php endforeach; ?>
                </select>
                <!-- + icon -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEntryModal">
                    <i class="bi bi-plus"></i>
                </button>
            </div>
        </form>
    </div>
<?php endif; ?>
<?php
// Fetch user site info
$sites = $DB->get_records('externship_sites', ['userid' => $selecteduserid]);
$hasSite = !empty($sites);
$firstSiteId = $hasSite ? array_values($sites)[0]->id : 0;
?>

<!-- Single Modal for Entry -->
<div class="modal fade" id="addEntryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title">Add Externship Entry</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      
      <div class="modal-body">
        <ul class="nav nav-tabs" id="entryTab" role="tablist">
          <?php if (!$hasSite): ?>
          <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#siteTab" type="button">Site Info</button>
          </li>
          <?php endif; ?>
          <li class="nav-item">
            <button class="nav-link <?= $hasSite ? 'active' : '' ?>" data-bs-toggle="tab" data-bs-target="#timesheetTab" type="button">Timesheet</button>
          </li>
        </ul>

        <div class="tab-content mt-3">

          <!-- Site Form -->
          <?php if (!$hasSite): ?>
          <div class="tab-pane fade show active" id="siteTab">
            <form method="post" action="save_entry.php" id="siteForm">
              <input type="hidden" name="userid" value="<?= $selecteduserid ?>">
              <input type="hidden" name="entrytype" value="site">

              <div class="mb-3">
                <label>Company Name</label>
                <input type="text" name="companyname" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Address</label>
                <textarea name="address" class="form-control"></textarea>
              </div>
              <div class="mb-3">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control">
              </div>
              <div class="mb-3">
                <label>Supervisor</label>
                <input type="text" name="supervisor" class="form-control">
              </div>
           <div class="mb-3">
  <label>User Type</label>
  <select name="usertype" class="form-select" required>
    <option value="internal">Internal User</option>
    <option value="external">External User</option>
  </select>
</div>

<div class="mb-3">
  <label>Start Date</label>
  <input type="date" name="startdate" class="form-control" required>
</div>


              <div class="modal-footer">
                <button type="submit" class="btn btn-success" id="saveSiteBtn">Save & Next</button>
              </div>
            </form>
          </div>
          <?php endif; ?>

          <!-- Timesheet Form -->
          <div class="tab-pane fade <?= $hasSite ? 'show active' : '' ?>" id="timesheetTab">
            <form method="post" action="save_entry.php" id="timesheetForm">
              <input type="hidden" name="userid" value="<?= $selecteduserid ?>">
              <input type="hidden" name="entrytype" value="timesheet">
              <?php if ($hasSite): ?>
              <input type="hidden" name="siteid" value="<?= $firstSiteId ?>">
              <?php endif; ?>

              <div class="mb-3">
                <label>Date</label>
                <input type="date" name="externdate" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Start Time</label>
                <input type="time" name="starttime" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>End Time</label>
                <input type="time" name="endtime" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Attend Hours</label>
                <input type="number" step="0.01" name="attendhrs" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Sched Hours</label>
                <input type="number" step="0.01" name="schedhrs" class="form-control" required>
              </div>

              <div class="modal-footer">
                <button type="submit" class="btn btn-success">Save Timesheet</button>
              </div>
            </form>
          </div>

        </div>
      </div>

    </div>
  </div>
</div>






         <div class="dashboardCircleBoxExternship">
    <div class="row px-4 py-4">
        <!-- ALL SITES with circular progress -->
        <div class="col-lg-4 asCircleBoxExternCont">
            <h5>ALL SITES</h5>
         

<?php
    // Example: use your dynamic variable here
    $percentage = isset($percentage) ? $percentage : 0; 
?>
<div class="circleWrapper">
    <div class="position-relative d-inline-block" style="width: 140px; height: 140px;">
        <svg width="140" height="140">
            <!-- Background Circle -->
            <circle cx="70" cy="70" r="60" stroke="#e5e7eb" stroke-width="12" fill="none"></circle>
            <!-- Progress Circle -->
            <?php
                $radius = 60;
                $circumference = 2 * M_PI * $radius;
                $offset = $circumference - ($percentage / 100) * $circumference;
            ?>
            <circle 
                class="progressCircle" 
                cx="70" cy="70" r="60"
                stroke="#0D9A00"
                stroke-width="12"
                fill="none"
                stroke-dasharray="<?= $circumference ?>"
                stroke-dashoffset="<?= $offset ?>"
                transform="rotate(-90 70 70)"
                style="transition: stroke-dashoffset 1s ease;"
            ></circle>
        </svg>
        <div id="progressValue"
            class="position-absolute top-50 start-50 translate-middle fs-2 fw-bold text-success">
            <?= intval($percentage) ?>
        </div>
    </div>
</div>


            <ul class="mt-3">
                <li>Total Extern Hours Required to Graduate: <strong><?= $total_required ?></strong></li>
                <li>Total Approved Hours Across all Sites: <strong><?= $approved ?></strong></li>
                <li>Total Pending Hours to be Approved by Employer: <strong><?= $pending ?></strong></li>
            </ul>
            <div class="asCircleBoxExternContPara"><?= $greeting ?></div>
        </div>

        <!-- Externship Details -->
        <div class="col-lg-8">
            <div class="dashboardExternshipBox">
                <h5 class="EXTERNSHIPDetails">EXTERNSHIP DETAILS:</h5>
                <?php if ($sites) : ?>
                    <?php foreach ($sites as $site) : ?>
                      <div class="asdasResulExtColMain mb-3">
    <div class="asdasResulExtCol mb-2">
        <i class="bi bi-building me-2 text-primary"></i>
        <strong>Location/Company Name :</strong> <?= $site->companyname ?>
    </div>

    <div class="asdasResulExtCol mb-2">
        <i class="bi bi-geo-alt me-2 text-danger"></i>
        <strong>Location Address :</strong> <?= $site->address ?>
    </div>

    <div class="asdasResulExtCol mb-2">
        <i class="bi bi-telephone me-2 text-success"></i>
        <strong>Location Phone Number :</strong> <?= $site->phone ?>
    </div>

    <div class="asdasResulExtCol mb-2">
        <i class="bi bi-person-badge me-2 text-warning"></i>
        <strong>Externship Supervisor :</strong> <?= $site->supervisor ?>
    </div>

    <div class="asdasResulExtCol mb-2">
        <i class="bi bi-calendar-date me-2 text-info"></i>
        <strong>Externship Start Date with this Location :</strong> <?= date('m/d/Y', strtotime($site->startdate)) ?>
    </div>
</div>

                    <?php endforeach; ?>
                <?php else : ?>
                    <p>No externship site assigned yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

            <div class="col-12 mt-4">
                <div class="dashboardCircleBoxExternship">
                    <h5 class="asDashboardSectionTitle mb-3">TIMESHEET DETAILS</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Extern Date</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Attend Hrs</th>
                                    <th>Sched Hrs</th>
                                    <th>Status</th>
                                    <?php if ($isadmin || $ismanager) : ?>

                                        <th>Action</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($timesheets) : ?>
                                    <?php foreach ($timesheets as $row) : ?>
                                        <tr>
                                          <td><?= date('m/d/Y', strtotime($row->externdate)) ?></td>
                                            <td><?= $row->starttime ?></td>
                                            <td><?= $row->endtime ?></td>
                                           <td class="attend-hrs-cell" data-id="<?= $row->id ?>" data-approved="<?= $row->status === 'Approved' ? 1 : 0 ?>">
                                           <span class="attend-value"><?= $row->attendhrs ?></span>
                                            <?php if ($row->status !== 'Approved'): ?>
                                             <span class="edit-attend ms-2" style="cursor:pointer;">
                                                 <i class="bi bi-pencil-square"></i>
                                                     </span>
                                                 <?php endif; ?>
                                               </td>

                                            <td><?= $row->schedhrs ?></td>
                                            <td class="<?= strtolower($row->status) ?>"><?= $row->status ?></td>
                                            <?php if ($isadmin || $ismanager) : ?>

                                                <td>
                                                    <form method="post" action="timesheet_action.php" class="d-flex gap-2">
                                                        <input type="hidden" name="id" value="<?= $row->id ?>">
                                                        <button name="status" value="Approved" class="btn btn-success btn-sm">Approve</button>
                                                        <button name="status" value="Rejected" class="btn btn-danger btn-sm">Reject</button>
                                                    </form>
                                                </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr><td colspan="7" class="text-center">No timesheet records found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </section>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<!-- JS to switch tab after Site Save -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  var siteForm = document.getElementById('siteForm');
  if (siteForm) {
    siteForm.addEventListener('submit', function(e) {
      // Optional: prevent default for AJAX, else page reload handles it
      // e.preventDefault();
      // After save, switch to timesheet tab
      var timesheetTab = new bootstrap.Tab(document.querySelector('#timesheetTab'));
      timesheetTab.show();
    });
  }

    // Inline edit for Attend Hrs
    document.querySelectorAll('.edit-attend').forEach(function(editBtn) {
        editBtn.addEventListener('click', function() {
            const td = this.closest('.attend-hrs-cell');
            const approved = td.getAttribute('data-approved');
            if (approved === "1") return; // disable if approved

            const id = td.getAttribute('data-id');
            const valueSpan = td.querySelector('.attend-value');
            const currentValue = valueSpan.innerText;

            // Replace span with input
            const input = document.createElement('input');
            input.type = 'number';
            input.step = '0.01';
            input.min = 0;
            input.value = currentValue;
            input.style.width = '60px';
            td.innerHTML = '';
            td.appendChild(input);
            input.focus();

            // Save on blur or Enter
            input.addEventListener('blur', saveValue);
            input.addEventListener('keydown', function(e) {
                if(e.key === 'Enter') saveValue.call(this);
            });

            function saveValue() {
                const newValue = this.value;

                // AJAX to save updated hours
                fetch('update_attendhrs.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${id}&attendhrs=${newValue}`
                }).then(res => res.json())
                .then(data => {
                    if(data.success){
                        td.setAttribute('data-approved', 0);
                        td.innerHTML = `
                            <span class="attend-value">${newValue}</span>
                            <span class="edit-attend ms-2" style="cursor:pointer;">
                                <i class="bi bi-pencil-square"></i>
                            </span>
                        `;
                        td.querySelector('.edit-attend').addEventListener('click', editBtn.click);
                    } else {
                        alert('Failed to update hours.');
                        td.innerHTML = `
                            <span class="attend-value">${currentValue}</span>
                            <span class="edit-attend ms-2" style="cursor:pointer;">
                                <i class="bi bi-pencil-square"></i>
                            </span>
                        `;
                    }
                });
            }
        });
    });
});
</script>
</body>
</html>
