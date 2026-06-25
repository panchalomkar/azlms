<?php
require_once('../config.php');
require_login();

global $USER, $CFG, $DB;

$logouturl = new moodle_url('/login/logout.php', array('sesskey' => sesskey()));

$PAGE->set_url(new moodle_url('/local/mygrades.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title("My Grades | Arizona School Medical Assistant");
$PAGE->set_heading("My Grades");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Grades | Arizona School Medical Assistant</title>
    <link rel="icon" type="image/x-icon" href="assets/images/common/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/css/global.css" />
       <?php
        require_once('head.php');
        ?>
</head>

<body>
    <main class="asDashboardMain d-flex">
        <?php require_once('lefnav.php'); ?>
        <section class="flex-grow-1">
            <?php require_once('hederu.php'); ?>
            <div class="dashboardBody forDashboardTableBody">
                <div class="asDMyGradesTableBox">
                    <h5 class="asDashboardSectionTitle mb-3">MY GRADES</h5>
                    <div class="table-responsive">
                        <table class="table asDashboardTable table-bordered align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Class Code</th>
                                    <th>Class Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Grade</th>
                                    <th>Credits</th>
                                    <th>SCH. HRS</th>
                                    <th>ATD. HRS</th>
                                    <th>ATD %</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Get enrolled courses for the logged-in user
                                $sql = "SELECT c.id, c.idnumber, c.fullname, c.startdate, c.enddate
                                        FROM {user_enrolments} ue
                                        JOIN {enrol} e ON e.id = ue.enrolid
                                        JOIN {course} c ON c.id = e.courseid
                                        WHERE ue.userid = :userid
                                        GROUP BY c.id";
                                $courses = $DB->get_records_sql($sql, ['userid' => $USER->id]);

                                foreach ($courses as $course) {
                                    $startdate = $course->startdate ? date('m/d/Y', $course->startdate) : '';
                                    $enddate   = $course->enddate ? date('m/d/Y', $course->enddate) : '';

                                    // Final course grade
                                    $grade = $DB->get_field_sql("
                                        SELECT ROUND(g.finalgrade, 0)
                                        FROM {grade_items} gi
                                        JOIN {grade_grades} g ON g.itemid = gi.id
                                        WHERE gi.courseid = :cid 
                                          AND gi.itemtype = 'course' 
                                          AND g.userid = :uid
                                    ", ['cid'=>$course->id, 'uid'=>$USER->id]);

                                    // Credits (custom field id=1) and SCH HRS (custom field id=2)
                                    $credits = $DB->get_field('customfield_data', 'value',
                                        ['instanceid'=>$course->id, 'fieldid'=>1]);
                                    $schhrs  = $DB->get_field('customfield_data', 'value',
                                        ['instanceid'=>$course->id, 'fieldid'=>2]);

                                    if (!$credits) $credits = "-";
                                    if (!$schhrs) $schhrs = "-";

                                    // Attendance defaults
$atdhrs = 0;
$atdperc = 0;

$total_possible_hrs = 0;

if ($DB->get_manager()->table_exists('attendance_sessions')) {
    // Get all sessions and student attendance for this course
    $sessions = $DB->get_records_sql("
        SELECT s.id, s.duration, al.statusid
        FROM {attendance_sessions} s
        JOIN {attendance_log} al ON al.sessionid = s.id
        JOIN {attendance} a ON a.id = s.attendanceid
        WHERE al.studentid = :uid AND a.course = :cid
    ", ['uid' => $USER->id, 'cid' => $course->id]);

    if ($sessions) {
        foreach ($sessions as $sess) {
            // Duration in hours
            $duration_in_hours = $sess->duration / 3600; // Moodle stores duration in seconds

            // Get the status acronym for this attendance
            $status = $DB->get_record('attendance_statuses', ['id' => $sess->statusid], 'acronym');
            if ($status) {
                switch ($status->acronym) {
                    case 'P': // Present
                    case 'E': // Excused
                        $atdhrs += $duration_in_hours; // full session counted
                        break;
                    case 'L': // Late
                        $atdhrs += $duration_in_hours * 0.5; // half session counted
                        break;
                    case 'A': // Absent
                        // 0 hours, do nothing
                        break;
                }
            }

            // Total possible hours always include all sessions
            $total_possible_hrs += $duration_in_hours;
        }
        
        // Attendance percentage
        $atdperc = ($total_possible_hrs > 0) ? round(($atdhrs / $total_possible_hrs) * 100, 2) : 0;
        $atdhrs = round($atdhrs, 2);
    }
}



// print_r($attendance);
                                    echo "<tr>
                                        <td>{$course->idnumber}</td>
                                        <td>{$course->fullname}</td>
                                        <td>{$startdate}</td>
                                        <td>{$enddate}</td>
                                        <td>".($grade ?? "-")."</td>
                                        <td>{$credits}</td>
                                        <td>{$schhrs}</td>
                                        <td>{$atdhrs}</td>
                                        <td>{$atdperc} %</td>
                                    </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <footer class="asDashboardFooter text-center p-3">
                © Copyright 2025. All rights reserved.
            </footer>
        </section>
    </main>
    <script src="../assets/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
