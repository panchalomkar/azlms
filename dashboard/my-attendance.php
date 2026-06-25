<?php
require_once('../config.php');
require_login();
$context = context_system::instance();
$isadmin = has_capability('mod/attendance:viewreports', $context);
require_once($CFG->libdir . '/moodlelib.php');
$logouturl = new moodle_url('/login/logout.php', array('sesskey' => sesskey()));
global $DB, $USER;

// Fetch attendance sessions + logs
if ($isadmin) {
    // Admin: all users
    $sql = "SELECT s.id AS sessionid, s.sessdate, s.duration, 
                   l.statusid, st.acronym, st.description, 
                   c.fullname AS coursename,
                   u.id AS userid, u.firstname, u.lastname, u.username
              FROM {attendance} a
         JOIN {course} c ON c.id = a.course
         JOIN {attendance_sessions} s ON s.attendanceid = a.id
         JOIN {attendance_log} l ON l.sessionid = s.id
         JOIN {attendance_statuses} st ON st.id = l.statusid
         JOIN {user} u ON u.id = l.studentid
          ORDER BY s.sessdate ASC";
    $records = $DB->get_records_sql($sql);
} else {
    // Normal user
    $sql = "SELECT s.id AS sessionid, s.sessdate, s.duration, 
                   l.statusid, st.acronym, st.description, 
                   c.fullname AS coursename
              FROM {attendance} a
         JOIN {course} c ON c.id = a.course
         JOIN {attendance_sessions} s ON s.attendanceid = a.id
         JOIN {attendance_log} l ON l.sessionid = s.id
         JOIN {attendance_statuses} st ON st.id = l.statusid
             WHERE l.studentid = :userid
          ORDER BY s.sessdate ASC";
    $params = ['userid' => $USER->id];
    $records = $DB->get_records_sql($sql, $params);
}

// Build JS-friendly array
$attendanceData = [];
$attendanceData = [];
foreach ($records as $r) {
    $date = date("Y-m-d", $r->sessdate);
    $hours = $r->duration > 0 ? round($r->duration / 3600, 1) : 0;
    $sessionName = "Session " . $r->sessionid;

    if ($isadmin) {
        // Just append to the date — no nested array
        $attendanceData[$date][] = [
            'userid'      => $r->userid,
            'name'        => fullname($r),
            'class'       => $r->coursename,
            'sessionname' => $sessionName,
            'time'        => $r->description,
            'hours'       => $hours,
            'status'      => $r->acronym
        ];
    } else {
        $attendanceData[$date][] = [
            'class'       => $r->coursename,
            'sessionname' => $sessionName,
            'time'        => $r->description,
            'hours'       => $hours,
            'status'      => $r->acronym
        ];
    }
}

// REMOVE the flattening code completely


// print_r($attendanceData);
// Array ( [2025-08-27] => Array ( [0] => Array ( [userid] => 8 [name] => Ruby user [class] => MA SkillsBuilder™ : Clinical Plus 2.0 [sessionname] => Session 3 [time] => Present [hours] => 0 [status] => P ) [1] => Array ( [userid] => 8 [name] => Ruby user [class] => MA SkillsBuilder™ : Clinical Plus 2.0 [sessionname] => Session 2 [time] => Present [hours] => 1 [status] => P ) ) [2025-09-24] => Array ( [0] => Array ( [userid] => 75 [name] => Mohan pal [class] => Medical Assistant Online 8.4.25 Start [sessionname] => Session 19 [time] => Present [hours] => 0.2 [status] => P ) ) [2025-09-29] => Array ( [0] => Array ( [userid] => 136 [name] => user two [class] => MA online [sessionname] => Session 22 [time] => Late [hours] => 0 [status] => L ) [1] => Array ( [userid] => 136 [name] => user two [class] => MA online [sessionname] => Session 23 [time] => Present [hours] => 0 [status] => P ) ) )
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title> My Attendance | Arizona School Medical Assistant</title>
<link rel="icon" type="image/x-icon" href="assets/images/common/logo.png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/global.css" />
<style>
.calendar-container { background: #fff; border-radius: 12px; padding: 20px; }
.calendar-grid { margin-top: 15px; }
.calendar-days { display: grid; grid-template-columns: repeat(7, 1fr); font-weight: bold; text-align: center; }
.calendar-dates { display: grid; grid-template-columns: repeat(7, 1fr); gap: 5px; }
.calendar-cell { border: 1px solid #ddd; min-height: 80px; padding: 5px; border-radius: 6px; font-size: 12px; cursor: pointer; }
.calendar-cell .cal-date { font-weight: bold; margin-bottom: 5px; }
.calendar-cell.present { background: #d4f8d4; }
.calendar-cell.absent { background: #f8d4d4; }
.calendar-cell.late { background: #fff3cd; }
.calendar-cell.empty { background: transparent; border: none; cursor: default; }
.calendar-cell.today {
    border: 2px solid #003152; /* primary color */
    background: #e8f4ff;       /* light highlight */
    font-weight: bold;
}

</style>
<?php require_once('head.php'); ?>
</head>
<body>
<main class="asDashboardMain d-flex">
<?php require_once('lefnav.php'); ?>
<section class="flex-grow-1">
<?php require_once('hederu.php'); ?>
<div class="asDashboardMyAttendance calendar-container">
    <div class="asDMyGradesTableBox">
        <h5 class="asDashboardSectionTitle mb-3">MY ATTENDANCE</h5>
        <div class="calendar-header">
            <div class="calendar-nav d-flex justify-content-between align-items-center">
                <button id="prevMonthBtn" class="btn btn-light">⟨</button>
                <span id="calendarMonthYear" class="fw-bold"></span>
                <button id="nextMonthBtn" class="btn btn-light">⟩</button>
            </div>
        </div>
        <div class="calendar-grid">
            <div class="calendar-days mb-3">
                <div>MON</div><div>TUE</div><div>WED</div>
                <div>THU</div><div>FRI</div><div>SAT</div><div>SUN</div>
            </div>
            <div id="calendarDates" class="calendar-dates"></div>
        </div>
    </div>
</div>
<footer class="asDashboardFooter text-center p-3">
    © Copyright 2025. All rights reserved.
</footer>
</section>
</main>

<!-- Attendance Modal -->
<div class="modal fade" id="attendanceModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Attendance Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="attendanceModalBody"></div>
    </div>
  </div>
</div>

<script>
const calendarDates = document.getElementById("calendarDates");
const monthYear = document.getElementById("calendarMonthYear");
const prevMonthBtn = document.getElementById("prevMonthBtn");
const nextMonthBtn = document.getElementById("nextMonthBtn");
const attendanceData = <?php echo json_encode($attendanceData); ?>;
const isAdmin = <?php echo $isadmin ? 'true' : 'false'; ?>;

let today = new Date();

function renderCalendar(date) {
    const year = date.getFullYear();
    const month = date.getMonth();
    const firstDay = new Date(year, month, 1);
    const startDay = (firstDay.getDay() + 6) % 7; // Monday start
    const totalDays = new Date(year, month + 1, 0).getDate();
    calendarDates.innerHTML = "";
    monthYear.textContent = date.toLocaleString("default", { month: "short", year: "numeric" });

    for (let i = 0; i < startDay; i++) calendarDates.innerHTML += `<div class="calendar-cell empty"></div>`;

   for (let day = 1; day <= totalDays; day++) {
    const dateStr = `${year}-${String(month+1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
    const sessions = attendanceData[dateStr];

    // check if this is today
    const isToday = (dateStr === new Date().toISOString().split("T")[0]);

    if (sessions) {
        const statusClass = sessions.some(s => s.status==='A') ? 'absent' 
                          : (sessions.some(s => s.status==='L') ? 'late' : 'present');
        let info = '';
        if (isAdmin) {
            const userCount = sessions.length;
            info = `<div>${userCount} User${userCount>1?'s':''}</div>`;
        } else {
            info = sessions.length>1 
                ? `<div>${sessions.length} Sessions</div>` 
                : `<div>${sessions[0].class}</div><div>${sessions[0].status}</div>`;
        }
        calendarDates.innerHTML += `<div class="calendar-cell ${statusClass} ${isToday?'today':''}" data-date="${dateStr}">
            <div class="cal-date">${day}</div><div class="cal-info">${info}</div></div>`;
    } else {
        calendarDates.innerHTML += `<div class="calendar-cell ${isToday?'today':''}">${day}</div>`;
    }
}


    document.querySelectorAll(".calendar-cell[data-date]").forEach(cell=>{
        cell.addEventListener("click", ()=>{
            const dateStr = cell.dataset.date;
            const sessions = attendanceData[dateStr];
            if(!sessions) return;
            let html = `<h6>${dateStr}</h6>`;
            if(isAdmin){
                html += `<div class="table-responsive"><table class="table table-bordered"><thead>
                <tr><th>User</th><th>Course</th><th>Session</th><th>Time</th><th>Hours</th><th>Status</th></tr>
                </thead><tbody>`;
                sessions.forEach(s=>{
                    html += `<tr><td>${s.name}</td><td>${s.class}</td><td>${s.sessionname}</td><td>${s.time}</td><td>${s.hours}</td>
                    <td><span class="badge bg-${s.status==='P'?'success':(s.status==='A'?'danger':'warning')}">${s.status}</span></td></tr>`;
                });
                html += `</tbody></table></div>`;
            } else {
                html += `<ul class="list-group">`;
                sessions.forEach(s=>{
                    html += `<li class="list-group-item d-flex justify-content-between"><span><strong>${s.class}</strong> (${s.sessionname}) - ${s.time}</span>
                    <span class="badge bg-${s.status==='P'?'success':(s.status==='A'?'danger':'warning')}">${s.status}</span></li>`;
                });
                html += `</ul>`;
            }
            document.getElementById("attendanceModalBody").innerHTML = html;
            new bootstrap.Modal(document.getElementById("attendanceModal")).show();
        });
    });
}

prevMonthBtn.onclick = () => { today.setMonth(today.getMonth()-1); renderCalendar(today); };
nextMonthBtn.onclick = () => { today.setMonth(today.getMonth()+1); renderCalendar(today); };
renderCalendar(today);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
