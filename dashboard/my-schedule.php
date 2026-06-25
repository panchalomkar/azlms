<?php
require_once('../config.php');
require_login();

global $USER, $CFG, $DB;

$logouturl = new moodle_url('/login/logout.php', array('sesskey' => sesskey()));

$PAGE->set_url(new moodle_url('/local/myschedule.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title("My Schedule | Arizona School Medical Assistant");
$PAGE->set_heading("My Schedule");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Schedule | Arizona School Medical Assistant</title>
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
                    <h5 class="asDashboardSectionTitle mb-3">MY SCHEDULE</h5>
                    <div class="table-responsive">
                        <table class="table asDashboardTable table-bordered align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Class Code</th>
                                    <th>Class Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Session</th>
                                    <th>On Campus Lab Days</th>
                                    <th>SOAR Classroom Day</th>
                                    <th>Instructors</th>
                                    <th>Room</th>
                                    <th>Grade</th>
                                    <th>Credits</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Get enrolled courses
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

                                    // Final Grade
                                    $grade = $DB->get_field_sql("
                                        SELECT ROUND(g.finalgrade, 0)
                                        FROM {grade_items} gi
                                        JOIN {grade_grades} g ON g.itemid = gi.id
                                        WHERE gi.courseid = :cid
                                          AND gi.itemtype = 'course'
                                          AND g.userid = :uid
                                    ", ['cid' => $course->id, 'uid' => $USER->id]);

                                    // Custom fields (adjust field IDs or shortnames as per your setup)
                                    $session       = $DB->get_field('customfield_data', 'value', ['instanceid'=>$course->id, 'fieldid'=>3]); // e.g. session time
                                    $labdays       = $DB->get_field('customfield_data', 'value', ['instanceid'=>$course->id, 'fieldid'=>4]); // lab days
                                    $soarday       = $DB->get_field('customfield_data', 'value', ['instanceid'=>$course->id, 'fieldid'=>5]); // SOAR classroom day
                                    $instructors   = $DB->get_field('customfield_data', 'value', ['instanceid'=>$course->id, 'fieldid'=>6]); // instructor names
                                    $room          = $DB->get_field('customfield_data', 'value', ['instanceid'=>$course->id, 'fieldid'=>7]); // room number
                                    $credits       = $DB->get_field('customfield_data', 'value', ['instanceid'=>$course->id, 'fieldid'=>1]); // credits

                                    // Fallbacks
                                    $session = $session ?: "-";
                                    $labdays = $labdays ?: "-";
                                    $soarday = $soarday ?: "-";
                                    $instructors = $instructors ?: "-";
                                    $room = $room ?: "TBA";
                                    $credits = $credits ?: "-";
                                    $grade = $grade ?: "-";

                                    echo "<tr>
                                        <td>{$course->idnumber}</td>
                                        <td>{$course->fullname}</td>
                                        <td>{$startdate}</td>
                                        <td>{$enddate}</td>
                                        <td>{$session}</td>
                                        <td>{$labdays}</td>
                                        <td>{$soarday}</td>
                                        <td>{$instructors}</td>
                                        <td>{$room}</td>
                                        <td>{$grade}</td>
                                        <td>{$credits}</td>
                                    </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script src="../assets/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
