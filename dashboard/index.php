<?php

require_once('../config.php');
global $USER, $CFG;

require_once($CFG->libdir . '/moodlelib.php');
$logouturl = new moodle_url('/login/logout.php', array('sesskey' => sesskey()));
require_login();
$managerroleid = 9;

// 1️⃣ Get all users who have the 'manager' custom profile field filled (email-based).
$users = $DB->get_records_sql("
    SELECT u.id AS userid, uid.data AS manageremail
      FROM {user_info_data} uid
      JOIN {user_info_field} uif ON uif.id = uid.fieldid
      JOIN {user} u ON u.id = uid.userid
     WHERE uif.shortname = :shortname
       AND uid.data IS NOT NULL
       AND uid.data != ''
", ['shortname' => 'manager']);

$countassigned = 0;

foreach ($users as $user) {
    // 2️⃣ Find the manager by email.
    $manager = $DB->get_record('user', ['email' => trim($user->manageremail)], '*', IGNORE_MISSING);

    if (!$manager) {
        continue;
    }

    // 3️⃣ Check if already assigned.
    $existing = $DB->get_record('role_assignments', [
        'roleid' => $managerroleid,
        'userid' => $manager->id,
        'contextid' => context_system::instance()->id
    ]);

    if (!$existing) {
        // 4️⃣ Assign the manager role at system level (can be changed to course level if needed).
        role_assign($managerroleid, $manager->id, context_system::instance()->id);
        // mtrace("✅ Assigned Manager Role to {$manager->email}");
        $countassigned++;
    } else {
        // mtrace("ℹ️ Manager {$manager->email} already assigned.");
    }
}

$isteacheroradmin = is_siteadmin();
$courses = enrol_get_users_courses($USER->id, true);
foreach ($courses as $c) {
    $ctx = context_course::instance($c->id);
    if (has_capability('moodle/course:update', $ctx)) {
        $isteacheroradmin = true;
        break;
    }
}
  $attendance_class = $isteacheroradmin ? 'd-none' : 'student-section';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title> Dashboard | Arizona School Medical Assistant</title>
    <link rel="icon" type="image/x-icon" href="assets/images/common/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/global.css" />
       <?php
        require_once('head.php');
        ?>
        <style>
            
            .course-card-img {
    height: 140px;
    background-size: cover;
    background-position: center;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

.dashboardGradeReport .card {
    border-radius: 12px;
    overflow: hidden;
}

.dashboardGradeReport .form-control,
.dashboardGradeReport .form-select {
    min-width: 160px;
}
            </style>
</head>

<body>
    <main class="asDashboardMain d-flex " >
       <?php
        require_once('lefnav.php');
        ?>
        <section class="flex-grow-1">
		<?php
        require_once('hederu.php');
        ?>
            
            <div class="dashboardBody">
                <div class="asDashboardBanner">
                    <div class="DBannerContentLeft">
                        <p><?=date('F j, Y')?></p>
                        <h5>Welcome back, <?=$USER->firstname?>!</h5>
                        <p>Always stay updated in your student portal</p>
                    </div>
                    <div class="DBannerContentRight">
                        <img src="../assets/images/home/hero-bg2.svg" alt="arrow sign">
                        <svg width="188" height="200" viewBox="0 0 188 200" fill="none">
                            <path
                                d="M129.25 35.5429C129.25 16.5156 112.87 0 94 0C75.1295 0 58.75 16.5156 58.75 35.5429C58.75 54.5702 75.1295 71.0858 94 71.0858C112.87 71.0858 129.25 54.5702 129.25 35.5429ZM94 82.9335C63.121 82.9335 35.25 116.522 35.25 158.261C35.25 200 152.75 200 152.75 158.261C152.75 116.522 124.879 82.9335 94 82.9335Z"
                                fill="#7081B9" />
                        </svg>
                    </div>
                </div>
                <!-- Shared Year/Month Filter -->
                 <div class="d-flex justify-content-end" style="widht:100%;">
<div class="insitesYandM d-flex mb-3 d-flex <?= $attendance_class ?>"  style="
    justify-content: end;
    align-items: center;
    border-radius: 5.995px;
    border: none;
    padding: 8.993px 17.985px;
    gap: 14px;
    margin-right: 20px;
    position: relative;
    top: 90px;
">
    <select id="insightYear" class="form-select w-auto" style="font-size: 14px;">
        <?php 
        $currentYear = date('Y');
        for ($y = $currentYear; $y >= $currentYear-3; $y--) {
            echo "<option ".($y==$currentYear?"selected":"").">$y</option>";
        }
        ?>
    </select>
    <select id="insightMonth" class="form-select w-auto ms-2" style="font-size: 14px;">
        <?php 
        for ($m = 1; $m <= 12; $m++) {
            $monthName = date('F', mktime(0, 0, 0, $m, 1));
            $selected = ($m == date('n')) ? "selected" : "";
            echo "<option value='$m' $selected>$monthName</option>";
        }
        ?>
    </select>
</div></div>

                <section class="asDashboardAttendanceSec asdashboardSecGap <?= $attendance_class ?>">
                    <div class="row g-5 align-items-stretch">
                        <div class="col-md-6 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-3 ">
                            <h5 class="asDashboardSectionTitle">Attendance</h5>
</div>
                            <?php
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

<style>
.calendar-container { background: #fff; border-radius: 12px; padding: 20px; }
.calendar-grid { margin-top: 15px; }
.calendar-days { border-radius:10px; display: grid; grid-template-columns: repeat(7, 1fr); font-weight: bold; text-align: center; }
.calendar-dates { display: grid; grid-template-columns: repeat(7, 1fr); gap: 5px; }
.calendar-cell { border: 1px solid #ddd;
    min-height: 50px;
    padding: 5px;
    border-radius: 55px;
    font-size: 12px;
    cursor: pointer;
    text-align: center;
    justify-content: center; }
.calendar-cell .cal-date { font-weight: bold; margin-bottom: 5px; }
.calendar-cell.present { background: #d4f8d4; }
.calendar-cell.absent { background: #f8d4d4; }
.calendar-cell.late { background: #fff3cd; }
.calendar-cell.empty { background: transparent; border: none; cursor: default; }
.calendar-cell{width: 50px;}
.calendar-cell .cal-info {
   font-size: 11px;
    line-height: 1.4;
    height: 45px;
    white-space: nowrap;       /* keep text on one line */
    overflow: hidden;          /* hide overflowing text */
    text-overflow: ellipsis;   /* add ... */
}
.calendar-cell.today {
    border: 2px solid #003152; /* primary color */
    background: #e8f4ff;       /* light highlight */
    font-weight: bold;
}
</style>
<?php require_once('head.php'); ?>
<main class="asDashboardMain d-flex mb-3" style="min-height:100px;">
<?php require_once('lefnav.php'); ?>
<section class="flex-grow-1">
<?php require_once('hederu.php'); ?>
<div class="asDashboardMyAttendance calendar-container" style="height: fit-content;">
    <div class="asDMyGradesTableBox" style="padding:20px">
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
<!-- code end -->
                        </div>
                       <!-- Insights Block -->
<div class="col-md-6 d-flex flex-column" style="height: fit-content;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="asDashboardSectionTitle mb-0">Insights</h5>
      
    </div>
    <div class="asDashboardInsightsBox flex-grow-1 d-flex flex-column justify-content-between">
        <div class="p-4 text-center">
            <div class="asDashboardOnTimeText d-flex text-left">On Time Percentage</div>
            <div class="asDashboardOnTimeMonth d-flex text-left" id="insightMonthLabel"><?=date('F')?></div>
            <div class="asDashboardOnTimePercent d-flex text-left" id="insightPercent">0%</div>
        </div>
        <div class="asDashboardChartPlaceholder mt-3">
            <!-- same chart placeholder -->
            <svg width="536" height="189" viewBox="0 0 536 189" fill="none">
                <path
                    d="M78.5348 84.8364C53.8564 124.13 13.0891 112.782 0 111.829V189H536V48.9496C536 48.9496 498.696 60.542 479.062 59.1129C462.952 57.9403 447.648 53.3966 424.088 33.3894C416.811 27.2097 402.491 3.37855 378.93 0.520464C340.197 -4.17821 320.663 23.8549 296.469 47.6803C263.092 80.5492 235.346 105.004 188.484 67.5277C170.813 53.3966 109.949 34.8184 78.5348 84.8364Z"
                    fill="url(#paint0_linear_268_1254)" />
                <defs>
                    <linearGradient id="paint0_linear_268_1254" x1="264.073" y1="-6.64319"
                        x2="264.073" y2="189" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#CDFF8C" />
                        <stop offset="1" stop-color="#52B623" stop-opacity="0" />
                    </linearGradient>
                </defs>
            </svg>
        </div>
    </div>
</div>

                    </div>
                </section>
                  <?php
require_once($CFG->libdir . '/gradelib.php');

global $USER, $DB;

$userid = $USER->id;
$courses = enrol_get_users_courses($userid, true);

$totalgpa   = 0;
$coursecount = 0;

foreach ($courses as $course) {
    // Fetch final course grade (rounded to integer).
    $grade = $DB->get_field_sql("
        SELECT ROUND(g.finalgrade, 0)
        FROM {grade_items} gi
        JOIN {grade_grades} g ON g.itemid = gi.id
        WHERE gi.courseid = :cid 
          AND gi.itemtype = 'course' 
          AND g.userid = :uid
    ", ['cid' => $course->id, 'uid' => $userid]);

    // Fetch Credits (fieldid = 1) and SCH HRS (fieldid = 12).
    $credits = $DB->get_field('customfield_data', 'value',
        ['instanceid' => $course->id, 'fieldid' => 1]);
    $schhrs = $DB->get_field('customfield_data', 'value',
        ['instanceid' => $course->id, 'fieldid' => 2]);

    // If grade exists, convert to GPA and add into totals.
    if (!is_null($grade)) {
        $percent = (float)$grade;
        $gpa = ($percent / 100) * 4.0;

        // For now, just use simple average (not weighted).
        $totalgpa += $gpa;
        $coursecount++;
    }

    // Optional: Debug/log per course
    /*

    */
}
// echo "<pre>Course: {$course->fullname}, Grade: {$grade}, GPA: {$gpa}, Credits: {$credits}, SCH HRS: {$schhrs}</pre>";
$cumulativegpa = $coursecount ? ($totalgpa / $coursecount) : 0;
$isTeacher = false;

if (is_siteadmin()) {
    $isTeacher = true;
} else {
   if (is_siteadmin()) {
    $courses = $DB->get_records_sql("
        SELECT id, fullname, shortname, summary, visible
        FROM {course}
        WHERE id <> 1
        ORDER BY fullname
    ");
} else {
    $courses = enrol_get_users_courses($USER->id, true);
}

    foreach ($courses as $c) {
        $ctx = context_course::instance($c->id);
        if (has_capability('moodle/course:update', $ctx)) {
            $isTeacher = true;
            break;
        }
    }
}


?>
<?php if ($isTeacher): ?>
<?php
require_once($CFG->dirroot . '/course/lib.php');

if ($isteacheroradmin):

    // Fetch teaching courses
 if (is_siteadmin()) {
    $teachingcourses = $DB->get_records_sql("
        SELECT id, fullname, shortname, summary, visible
        FROM {course}
        WHERE id <> 1
        ORDER BY fullname
    ");
} else {
    $teachingcourses = enrol_get_users_courses(
        $USER->id,
        true,
        ['id', 'fullname', 'shortname', 'summary', 'visible']
    );

    $teachingcourses = array_filter($teachingcourses, function($course) {
        $context = context_course::instance($course->id);
        return has_capability('moodle/course:update', $context);
    });
}

    // Helper: get course image
  function get_course_image($courseid) {
    global $CFG;

    $context = context_course::instance($courseid);
    $fs = get_file_storage();

    $files = $fs->get_area_files(
        $context->id,
        'course',
        'overviewfiles',
        false, // IMPORTANT: no itemid
        'sortorder, filepath, filename',
        false
    );

    if (!empty($files)) {
        $file = reset($files);

        return moodle_url::make_pluginfile_url(
            $context->id,
            'course',
            'overviewfiles',
            null, // ❌ NO itemid here
            $file->get_filepath(),
            $file->get_filename()
        )->out();
    }

    // Fallback image
     return 'https://dummyimage.com/600x300/003152/ffffff&text=Course+Image';
}

?>

<section class="dashboardGradeReport mt-4">

    <!-- HEADER + FILTERS -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h5 class="asDashboardSectionTitle mb-0">Courses</h5>

        <div class="d-flex gap-2">
            <input type="text"
                   id="courseSearch"
                   class="form-control form-control-sm"
                   placeholder="Search course...">

            <select id="courseFilter" class="form-select form-select-sm">
                <option value="all">All</option>
                <option value="visible">Visible</option>
                <option value="hidden">Hidden</option>
            </select>
        </div>
    </div>

    <?php if (empty($teachingcourses)): ?>
        <div class="alert alert-info">
            You are not assigned as a teacher in any course.
        </div>
    <?php else: ?>
       <div class="bg-white rounded mb-4 p-3">
    <div class="row" id="teachingCourseContainer">
        <?php 
        $counter = 0;
        foreach ($teachingcourses as $course):
            $imageurl = get_course_image($course->id);
            $visibility = $course->visible ? 'visible' : 'hidden';
        ?>
        <div class="col-md-3 mb-4 teaching-course-card"
             data-title="<?= strtolower(format_string($course->fullname)) ?>"
             data-visible="<?= $visibility ?>">
            <div class="card h-100 shadow-lg border-0">
                <div class="img-container p-3">
                    <div class="course-card-img"
                         style="background-image:url('<?= $imageurl ?>'); height:120px; background-size:cover; background-position:center;">
                    </div>
                </div>
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title fw-semibold mb-1"><?= format_string($course->fullname) ?></h6>
                    <p class="card-text text-muted small flex-grow-1"><?= shorten_text(strip_tags($course->summary), 90) ?></p>
                    <div class="d-flex flex-column gap-2 mt-2">
                        <a href="<?= $CFG->wwwroot ?>/course/view.php?id=<?= $course->id ?>"
                           class="btn btn-sm text-light" style="background:rgb(0 49 82);">
                            View Course
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="d-flex justify-content-center mt-3">
    <nav>
        <ul class="pagination pagination-sm mb-0 gap-2 d-flex">
            <li class="page-item">
                <button class="page-link" id="prevPage">Prev</button>
            </li>
            <li class="page-item disabled">
                <span class="page-link" id="pageInfo">Page 1</span>
            </li>
            <li class="page-item">
                <button class="page-link" id="nextPage">Next</button>
            </li>
        </ul>
    </nav>
</div>

</div>

    <?php endif; ?>
</section>

<?php endif; ?>


<section class="dashboardGradeReport mt-4">
    <div class="d-flex justify-content-between align-items-center  mb-3">
        <h5 class="asDashboardSectionTitle">Grade Report</h5>
    </div>

<object class="bg-white rounded mb-4"
    data="<?= $CFG->wwwroot ?>/local/incourse/grade_report.php?courseid=<?= $courseid ?>&embedded=1"
    type="text/html"
    style="width:100%; height:550px;">
</object>

</section>
<section class="dashboardActivityReport mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="asDashboardSectionTitle">Activity Report</h5>
    </div>

    <iframe class="bg-white rounded"
  src="<?= $CFG->wwwroot ?>/local/incourse/report/activity_report.php?courseid=<?= $courseid ?>&sectionid=<?= $sectionid ?>&embedded=1"
  style="width:100%; height:600px;"
  loading="lazy">
</iframe>

</section>

<section class="dashboardGradeReport ">
  <!-- ===== Forum Report ===== -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="asDashboardSectionTitle">Forum Report</h5>
    </div>

<object class="bg-white rounded mb-4"
    data="<?= $CFG->wwwroot ?>/local/incourse/forum_grade.php?courseid=<?= $courseid ?>&embedded=1"
    type="text/html"
    style="width:100%; height:550px;">
</object>
<section class="dashboardQuizReport">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="asDashboardSectionTitle">Quiz Report</h5>
    </div>

    <object class="bg-white rounded mb-4"
        data="<?= $CFG->wwwroot ?>/local/incourse/quiz_report.php?courseid=<?= $courseid ?>&embedded=1"
        type="text/html"
        style="width:100%; height:550px;">
    </object>
</section>
</section>


<?php endif; ?>

<section class="CumulativeBox asdashboardSecGap <?= $attendance_class ?>" style="padding:20px;">
    <h4 class="mt-2 d-flex justify-content-center gap-2 flex-column" style="text-align: center;">
        <strong>Cumulative GPA:</strong>
        <?php echo round($cumulativegpa, 2); ?>
    </h4>
   <p>This is your cumulative Grade Point Average (GPA). This is an average of all grades earned thus
                        far in
                        your program. Students are required to achieve a 2.0 cumulative GPA to receive externship or
                        graduation
                        clearance. If your GPA is under 2.0, please see your instructor or Director of Education to
                        review and
                        receive support!</p>
</section>
            </div>
        
        </section>
    </main>

    
    <script src="../assets/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
        crossorigin="anonymous"></script>
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

    for (let i = 0; i < startDay; i++) {
        calendarDates.innerHTML += `<div class="calendar-cell empty"></div>`;
    }

    for (let day = 1; day <= totalDays; day++) {
        const dateStr = `${year}-${String(month+1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
        const sessions = attendanceData[dateStr];

        const isToday = (dateStr === new Date().toISOString().split("T")[0]);

        if (sessions) {
            const statusClass = sessions.some(s => s.status==='A') ? 'absent' 
                              : (sessions.some(s => s.status==='L') ? 'late' : 'present');

            // Tooltip content
            let tooltipContent = '';
            if (isAdmin) {
                tooltipContent = sessions.map(s => 
                    `${s.name} - ${s.class} (${s.sessionname}) [${s.time}] - ${s.status}`
                ).join('\n');
            } else {
                tooltipContent = sessions.map(s => 
                    `${s.class} (${s.sessionname}) [${s.time}] - ${s.status}`
                ).join('\n');
            }

            calendarDates.innerHTML += `
                <div class="calendar-cell ${statusClass} ${isToday?'today':''}" 
                     data-date="${dateStr}" 
                     data-bs-toggle="tooltip" 
                     data-bs-placement="top" 
                     data-bs-html="true"
                     title="${tooltipContent.replace(/"/g, '&quot;')}">
                    <div class="cal-date">${day}</div>
                </div>`;
        } else {
            calendarDates.innerHTML += `<div class="calendar-cell ${isToday?'today':''}"><div class="cal-date">${day}</div></div>`;
        }
    }

    // Initialize tooltips after rendering
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Click handler to still open modal if needed
    document.querySelectorAll(".calendar-cell[data-date]").forEach(cell=>{
        cell.addEventListener("click", ()=>{
            const dateStr = cell.dataset.date;
            const sessions = attendanceData[dateStr];
            if(!sessions) return;
            let html = `<h6>${dateStr}</h6>`;
            if(isAdmin){
                html += `<div class="table-responsive"><table class="table table-bordered"><thead>
                <tr><th>User</th><th>Course</th><th>Session</th><th>Time</th><th>Status</th></tr>
                </thead><tbody>`;
                sessions.forEach(s=>{
                    html += `<tr><td>${s.name}</td><td>${s.class}</td><td>${s.sessionname}</td><td>${s.time}</td>
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

function updateInsights() {
    const year = document.getElementById("insightYear").value;
    const month = document.getElementById("insightMonth").value;
    const monthLabel = document.getElementById("insightMonthLabel");
    const percentEl = document.getElementById("insightPercent");

    let totalSessions = 0;
    let onTimeSessions = 0;

    for (const dateStr in attendanceData) {
        const d = new Date(dateStr);
        if (d.getFullYear() == year && (d.getMonth()+1) == month) {
            const sessions = attendanceData[dateStr];
            sessions.forEach(s => {
                totalSessions++;
                if (s.status === 'P') onTimeSessions++; // "Present" = On Time
            });
        }
    }

    let percent = totalSessions > 0 ? Math.round((onTimeSessions / totalSessions) * 100) : 0;
    percentEl.textContent = percent + "%";
    monthLabel.textContent = new Date(year, month-1, 1).toLocaleString('default', { month: 'long' });
}

// Hook change events
document.getElementById("insightYear").addEventListener("change", updateInsights);
document.getElementById("insightMonth").addEventListener("change", updateInsights);

function updateAttendanceCalendar() {
    const year = parseInt(document.getElementById("insightYear").value);
    const month = parseInt(document.getElementById("insightMonth").value) - 1;
    today = new Date(year, month, 1);
    renderCalendar(today);
}

// Initial load
// updateInsights();
function onFilterChange() {
    updateInsights();       // Update Insights block
    updateAttendanceCalendar(); // Refresh Attendance calendar
}

document.getElementById("insightYear").addEventListener("change", onFilterChange);
document.getElementById("insightMonth").addEventListener("change", onFilterChange);
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('courseSearch');
    const filterSelect = document.getElementById('courseFilter');
    const cards = document.querySelectorAll('.teaching-course-card');

    function applyFilters() {
        const search = searchInput.value.toLowerCase();
        const filter = filterSelect.value;

        cards.forEach(card => {
            const title = card.dataset.title;
            const visible = card.dataset.visible;

            let show = true;

            if (search && !title.includes(search)) {
                show = false;
            }

            if (filter !== 'all' && visible !== filter) {
                show = false;
            }

            card.style.display = show ? '' : 'none';
        });
    }

    searchInput.addEventListener('keyup', applyFilters);
    filterSelect.addEventListener('change', applyFilters);
});

document.addEventListener('DOMContentLoaded', function () {

    const cards = Array.from(document.querySelectorAll('.teaching-course-card'));
    const perPage = 8; // 2 rows × 4 columns
    let currentPage = 1;

    const searchInput = document.getElementById('courseSearch');
    const filterSelect = document.getElementById('courseFilter');

    function getFilteredCards() {
        const search = searchInput.value.toLowerCase();
        const filter = filterSelect.value;

        return cards.filter(card => {
            const title = card.dataset.title;
            const visible = card.dataset.visible;

            const matchSearch = title.includes(search);
            const matchFilter =
                filter === 'all' ||
                (filter === 'visible' && visible === 'visible') ||
                (filter === 'hidden' && visible === 'hidden');

            return matchSearch && matchFilter;
        });
    }

    function render() {
        const filtered = getFilteredCards();
        const totalPages = Math.ceil(filtered.length / perPage);

        if (currentPage > totalPages) currentPage = totalPages || 1;

        cards.forEach(c => c.style.display = 'none');

        const start = (currentPage - 1) * perPage;
        const end = start + perPage;

        filtered.slice(start, end).forEach(c => c.style.display = '');

        document.getElementById('pageInfo').innerText =
            `Page ${currentPage} of ${totalPages || 1}`;

        document.getElementById('prevPage').disabled = currentPage === 1;
        document.getElementById('nextPage').disabled = currentPage === totalPages;
    }

    document.getElementById('prevPage').onclick = () => {
        currentPage--;
        render();
    };

    document.getElementById('nextPage').onclick = () => {
        currentPage++;
        render();
    };

    searchInput.addEventListener('input', () => {
        currentPage = 1;
        render();
    });

    filterSelect.addEventListener('change', () => {
        currentPage = 1;
        render();
    });

    render();
});
</script>

</body>

</html>