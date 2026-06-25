<?php
require_once('../config.php');
global $USER, $CFG;
$context = context_user::instance($USER->id);
$PAGE->set_context($context);

require_once($CFG->libdir . '/moodlelib.php');
$logouturl = new moodle_url('/login/logout.php', array('sesskey' => sesskey()));
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
        <?php
        require_once('lefnav.php');
        ?>
        <section class="flex-grow-1">
            <?php
        require_once('hederu.php');
        ?>
            <div class="dashboardBody asDasprofileContainer">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="asDasprofileContainerLeft">
                            <div class="asDasprofileLUser">
                                <img src="../assets/images/dashboard/profileAvtar.svg" alt="profile avtar">
                                <h3><?php echo fullname($USER); ?></h3>
                                <p>Medical Assistant</p>
                                <p><?php echo $USER->id; ?></p>
                            </div>
                            <div class="asDasProfileArrIcon">
                                <img src="../assets/images/dashboard/arrowIcon.svg" alt="arrow icon">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 asDasprofileContainerRight">
                        <form>

                            <div class="formSectionCard mb-2">
                                <h5 class="mb-3">MY PROFILE</h5>
                                <div class="row gx-3 gy-2">
                                    <div class="formGroup contactFormGroup col-md-4">
                                        <label for="studentId">Student ID</label>
                                        <input type="text" id="studentId" name="studentId" value="<?php echo $USER->id; ?>">
                                    </div>
                                    <div class="formGroup contactFormGroup col-md-4">
                                        <label for="studentName">Student Name</label>
                                        <input type="text" id="studentName" name="studentName" value="<?php echo fullname($USER); ?>">
                                    </div>
                                    <div class="formGroup contactFormGroup col-md-4">
                                        <label for="studentEmail">Email Address</label>
                                        <input type="email" id="studentEmail" name="studentEmail" value="<?php echo $USER->email; ?>">
                                    </div>
                                    <div class="formGroup contactFormGroup col-md-12">
                                        <label for="studentStreet">Street Address</label>
                                        <textarea id="studentStreet" name="studentStreet"></textarea>
                                    </div>
                                    <div class="formGroup contactFormGroup col-md-4">
                                        <label for="studentCity">City</label>
                                        <input type="text" id="studentCity" name="studentCity" value="<?php echo $USER->city; ?>">
                                    </div>
                                    <div class="formGroup contactFormGroup col-md-4">
                                        <label for="studentState">State</label>
                                        <input type="text" id="studentState" name="studentState" value="">
                                    </div>
                                    <div class="formGroup contactFormGroup col-md-4">
                                        <label for="studentPostalCode">Postal Code</label>
                                        <input type="text" id="studentPostalCode" name="studentPostalCode" value="">
                                    </div>
                                    <div class="formGroup contactFormGroup col-md-4">
                                        <label for="studentHomePhone">Home Phone</label>
                                        <input type="text" id="studentHomePhone" name="studentHomePhone" value="">
                                    </div>
                                    <div class="formGroup contactFormGroup col-md-4">
                                        <label for="studentCellPhone">Cell Phone</label>
                                        <input type="text" id="studentCellPhone" name="studentCellPhone" value="">
                                    </div>
                                    <div class="formGroup contactFormGroup col-md-4">
                                        <label for="studentWorkPhone">Work Phone</label>
                                        <input type="text" id="studentWorkPhone" name="studentWorkPhone" value="">
                                    </div>
                                </div>
                            </div>

                            <div class="formSectionCard mb-4">
                                <h5 class="mb-3">MY PROGRAM</h5>
                                <div class="row gx-3 gy-2">
                                    <div class="formGroup contactFormGroup col-md-6">
                                        <label for="campus">Campus</label>
                                        <input type="text" id="campus" name="campus">
                                    </div>
                                    <div class="formGroup contactFormGroup col-md-6">
                                        <label for="programName">Program Name</label>
                                        <input type="text" id="programName" name="programName">
                                    </div>
                                    <div class="formGroup contactFormGroup col-md-4">
                                        <label for="session">Session</label>
                                        <input type="text" id="session" name="session">
                                    </div>
                                    <div class="formGroup contactFormGroup col-md-4">
                                        <label for="enrollmentStatus">Enrollment Status</label>
                                        <input type="text" id="enrollmentStatus" name="enrollmentStatus">
                                    </div>
                                    <div class="formGroup contactFormGroup col-md-4">
                                        <label for="ldaStatus">LDA Status</label>
                                        <input type="text" id="ldaStatus" name="ldaStatus">
                                    </div>
                                    <div class="formGroup contactFormGroup col-md-6">
                                        <label for="programStartDate">Program Start Date</label>
                                        <input type="date" id="programStartDate" name="programStartDate">
                                    </div>
                                    <div class="formGroup contactFormGroup col-md-6">
                                        <label for="projectedGradDate">Projected Grad Date</label>
                                        <input type="date" id="projectedGradDate" name="projectedGradDate">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h5 class="asDashboardSectionTitleForm mb-3">In case of emergency, Please contact:</h5>
                                <div class="formSectionCard mb-4" id="PrimarycontactAddMore">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5>PRIMARY CONTACT</h5>
                                        <button type="button" class="asDasPAddMoreBtn"><svg width="25" height="25"
                                                viewBox="0 0 25 25" fill="none">
                                                <path
                                                    d="M12.5 0C5.596 0 0 5.596 0 12.5C0 19.404 5.596 25 12.5 25C19.404 25 25 19.404 25 12.5C25 5.596 19.404 0 12.5 0ZM19 13.5H13.5V19C13.5 19.2652 13.3946 19.5196 13.2071 19.7071C13.0196 19.8946 12.7652 20 12.5 20C12.2348 20 11.9804 19.8946 11.7929 19.7071C11.6054 19.5196 11.5 19.2652 11.5 19V13.5H6C5.73478 13.5 5.48043 13.3946 5.29289 13.2071C5.10536 13.0196 5 12.7652 5 12.5C5 12.2348 5.10536 11.9804 5.29289 11.7929C5.48043 11.6054 5.73478 11.5 6 11.5H11.5V6C11.5 5.73478 11.6054 5.48043 11.7929 5.29289C11.9804 5.10536 12.2348 5 12.5 5C12.7652 5 13.0196 5.10536 13.2071 5.29289C13.3946 5.48043 13.5 5.73478 13.5 6V11.5H19C19.2652 11.5 19.5196 11.6054 19.7071 11.7929C19.8946 11.9804 20 12.2348 20 12.5C20 12.7652 19.8946 13.0196 19.7071 13.2071C19.5196 13.3946 19.2652 13.5 19 13.5Z"
                                                    fill="#2A3761" />
                                            </svg> Add
                                            More</button>
                                    </div>
                                    <div class="row gx-3 gy-2">
                                        <div class="formGroup contactFormGroup col-md-6">
                                            <label for="emergencyStudentId">Student ID</label>
                                            <input type="text" id="emergencyStudentId" name="emergencyStudentId">
                                        </div>
                                        <div class="formGroup contactFormGroup col-md-6">
                                            <label for="emergencyEmail">Email Address</label>
                                            <input type="email" id="emergencyEmail" name="emergencyEmail">
                                        </div>
                                        <div class="formGroup contactFormGroup col-md-12">
                                            <label for="emergencyStreet">Street Address</label>
                                            <textarea id="emergencyStreet" name="emergencyStreet"></textarea>
                                        </div>
                                        <div class="formGroup contactFormGroup col-md-4">
                                            <label for="emergencyCity">City</label>
                                            <input type="text" id="emergencyCity" name="emergencyCity">
                                        </div>
                                        <div class="formGroup contactFormGroup col-md-4">
                                            <label for="emergencyState">State</label>
                                            <input type="text" id="emergencyState" name="emergencyState">
                                        </div>
                                        <div class="formGroup contactFormGroup col-md-4">
                                            <label for="emergencyPostalCode">Postal Code</label>
                                            <input type="text" id="emergencyPostalCode" name="emergencyPostalCode">
                                        </div>
                                        <div class="formGroup contactFormGroup col-md-4">
                                            <label for="emergencyHomePhone">Home Phone</label>
                                            <input type="text" id="emergencyHomePhone" name="emergencyHomePhone">
                                        </div>
                                        <div class="formGroup contactFormGroup col-md-4">
                                            <label for="emergencyCellPhone">Cell Phone</label>
                                            <input type="text" id="emergencyCellPhone" name="emergencyCellPhone">
                                        </div>
                                        <div class="formGroup contactFormGroup col-md-4">
                                            <label for="emergencyWorkPhone">Work Phone</label>
                                            <input type="text" id="emergencyWorkPhone" name="emergencyWorkPhone">
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="d-flex justify-content-end gap-2">
                                <button type="reset" class="asDasPclearBtn">Clear</button>
                                <button type="submit" class="asDasPclearBtn saveAsDasPclearBtn">Save
                                    <span>
                                        <svg width="24" height="24" viewBox="0 0 54 54" fill="none">
                                            <path d="M29.2504 38.2499C..." fill="white" />
                                        </svg>
                                    </span>
                                </button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
            <footer class="asDashboardFooter text-center p-3">
                © Copyright 2025. All rights reserved.
            </footer>
        </section>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const addMoreBtn = document.querySelector("#PrimarycontactAddMore .asDasPAddMoreBtn");
            const contactContainer = document.getElementById("PrimarycontactAddMore");
            let contactCount = 1;

            addMoreBtn.addEventListener("click", function () {
                contactCount++;

                const clonedBlock = contactContainer.cloneNode(true);

                clonedBlock.id = `PrimarycontactAddMore_${contactCount}`;

                const inputs = clonedBlock.querySelectorAll("input, textarea");
                inputs.forEach(input => {
                    input.value = "";
                    if (input.name) {
                        input.name = input.name + "_" + contactCount;
                    }
                    if (input.id) {
                        input.id = input.id + "_" + contactCount;
                    }
                });

                const heading = clonedBlock.querySelector("h5");
                if (heading) {
                    heading.textContent = `PRIMARY CONTACT ${contactCount}`;
                }

                const clonedBtn = clonedBlock.querySelector(".asDasPAddMoreBtn");
                if (clonedBtn) {
                    clonedBtn.remove();
                }

                contactContainer.parentNode.appendChild(clonedBlock);
            });
        });
    </script>

    <script src="../assets/js/main.js"></script>
    <script src="/assets/js/calendar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
        crossorigin="anonymous"></script>
</body>


</html>