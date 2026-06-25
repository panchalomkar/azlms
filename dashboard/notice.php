<?php
require_once('../config.php');
global $USER, $CFG;

require_once($CFG->libdir . '/moodlelib.php');
$logouturl = new moodle_url('/login/logout.php', array('sesskey' => sesskey()));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title> My Grades | Arizona School Medical Assistant</title>
    <link rel="icon" type="image/x-icon" href="assets/images/common/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/global.css" />
</head>

<body>
    <main class="asDashboardMain d-flex">
        <?php
        require_once('lefnav.php');
        ?>
        <section class="flex-grow-1">
            <?php
        require_once('hederu.php');
        ?>
            <div class="dashboardBody forDashboardTableBody">
                <div class="row gx-4 gy-4">
                    <!-- Left Circle Summary -->
                    <div class="col-lg-4">
                        <div class="dashboardCircleBox h-100 p-4 rounded shadow-sm bg-white">
                            <h5 class="fw-semibold mb-3">ALL SITES</h5>
                            <div class="circleWrapper text-center mb-4">
                                <div class="position-relative d-inline-block" style="width: 140px; height: 140px;">
                                    <svg width="140" height="140">
                                        <circle cx="70" cy="70" r="60" stroke="#e5e7eb" stroke-width="10" fill="none" />
                                        <circle cx="70" cy="70" r="60" stroke="#22c55e" stroke-width="10" fill="none"
                                            stroke-dasharray="376.8" stroke-dashoffset="7.5"
                                            transform="rotate(-90 70 70)" />
                                    </svg>
                                    <div
                                        class="position-absolute top-50 start-50 translate-middle fs-2 fw-bold text-success">
                                        98</div>
                                </div>
                            </div>
                            <ul class="list-unstyled small mb-3">
                                <li class="mb-1">Total Extern Hours Required to Graduate: <strong>99</strong></li>
                                <li class="mb-1">Total Approved Hours Across all Sites: <strong>100</strong></li>
                                <li class="mb-1">Total Pending Hours to be Approved by Employer: <strong>0</strong></li>
                            </ul>
                            <p class="text-success fw-semibold m-0">Great job, you’re almost done!</p>
                        </div>
                    </div>

                    <!-- Right Externship Details -->
                    <div class="col-lg-8">
                        <div class="dashboardExternshipBox h-100 p-4 rounded shadow-sm bg-white">
                            <h5 class="fw-semibold mb-3">EXTERNSHIP DETAILS:</h5>
                            <div class="row gx-3 gy-2">
                                <div class="col-sm-6"><strong>Location/Company Name :</strong><br> Phoenix Area
                                    Cardiology and Electrophysiology</div>
                                <div class="col-sm-6"><strong>Location Address :</strong><br> 6502 N 35th Ave Ste 2,
                                    Phoenix, AZ</div>
                                <div class="col-sm-6"><strong>Location Phone Number :</strong><br> (602) 989-0725</div>
                                <div class="col-sm-6"><strong>Externship Supervisor :</strong><br> Semino, Jaleh</div>
                                <div class="col-sm-6"><strong>Externship Start Date with this Location :</strong><br>
                                    08/24/2023</div>
                            </div>
                        </div>
                    </div>

                    <!-- Timesheet Table -->
                    <div class="col-12">
                        <div class="dashboardTimesheetBox p-4 rounded shadow-sm bg-white mt-4">
                            <h5 class="fw-semibold mb-3">TIMESHEET DETAILS</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle text-center mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Extern Date</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Attend Hrs</th>
                                            <th>Sched Hrs</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>10/12/2023</td>
                                            <td>08:00 AM</td>
                                            <td>03:00 PM</td>
                                            <td>7</td>
                                            <td>7</td>
                                            <td class="text-success fw-medium">Approved</td>
                                        </tr>
                                        <tr>
                                            <td>10/12/2023</td>
                                            <td>08:00 AM</td>
                                            <td>03:00 PM</td>
                                            <td>7</td>
                                            <td>7</td>
                                            <td class="text-success fw-medium">Approved</td>
                                        </tr>
                                        <tr>
                                            <td>10/12/2023</td>
                                            <td>08:00 AM</td>
                                            <td>03:00 PM</td>
                                            <td>7</td>
                                            <td>7</td>
                                            <td class="text-success fw-medium">Approved</td>
                                        </tr>
                                        <tr>
                                            <td>10/12/2023</td>
                                            <td>08:00 AM</td>
                                            <td>03:00 PM</td>
                                            <td>7</td>
                                            <td>7</td>
                                            <td class="text-success fw-medium">Approved</td>
                                        </tr>
                                        <tr>
                                            <td>10/12/2023</td>
                                            <td>08:00 AM</td>
                                            <td>03:00 PM</td>
                                            <td>7</td>
                                            <td>7</td>
                                            <td class="text-success fw-medium">Approved</td>
                                        </tr>
                                        <tr>
                                            <td>10/12/2023</td>
                                            <td>08:00 AM</td>
                                            <td>03:00 PM</td>
                                            <td>7</td>
                                            <td>7</td>
                                            <td class="text-success fw-medium">Approved</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="asDashboardFooter text-center p-3">
                © Copyright 2025. All rights reserved.
            </footer>
        </section>
    </main>
    <script src="../assets/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
        crossorigin="anonymous"></script>
</body>

</html>