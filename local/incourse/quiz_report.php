<?php
require_once("../../config.php");
require_login();
$isadmin = is_siteadmin();
global $DB, $CFG, $OUTPUT, $PAGE;
$teacher_courses = [];

if ($isadmin) {
    // ADMIN → all courses
    $teacher_courses = $DB->get_records_menu(
        'course',
        null,
        'fullname ASC',
        'id, fullname'
    );
} else {
    // TEACHER → only enrolled courses where they can edit
    $enrolledcourses = enrol_get_users_courses($USER->id, true, ['id', 'fullname']);

    foreach ($enrolledcourses as $c) {
        $ccontext = context_course::instance($c->id);
        if (has_capability('moodle/course:update', $ccontext)) {
            $teacher_courses[$c->id] = $c->fullname;
        }
    }
}

$PAGE->set_context(context_system::instance());

/* =========================
   PARAMS
========================= */
$courseid  = optional_param('courseid', 0, PARAM_INT);
$sectionid = optional_param('sectionid', -1, PARAM_INT); // -1 = all sections
$export    = optional_param('export', 0, PARAM_BOOL);

/* =========================
   COURSE CHECK
========================= */
if ($courseid) {
    $course  = $DB->get_record('course', ['id'=>$courseid], '*', MUST_EXIST);
    $context = context_course::instance($courseid);
    require_capability('moodle/course:update', $context);
    $users   = get_enrolled_users($context);
    $modinfo = get_fast_modinfo($course);
}

/* =========================
   HELPER: Get section name
========================= */
/* =========================
   HELPER: Get section name
========================= */
if (!function_exists('local_get_section_name')) {
    function local_get_section_name($courseid, $sectionnum) {
        global $DB;
        if ($sectionnum <= 0) {
            return 'General';
        }
        $name = $DB->get_field('course_sections','name',['course'=>$courseid,'section'=>$sectionnum]);
        if (empty($name)) {
            return "Section {$sectionnum}";
        }
        return $name;
    }
}

if (!function_exists('local_get_highest_quiz_grade')) {
    function local_get_highest_quiz_grade($quizid) {
        global $DB;

        return $DB->get_field_sql("
            SELECT MAX(grade)
              FROM {quiz_grades}
             WHERE quiz = :quizid
        ", ['quizid' => $quizid]);
    }
}

/* =========================
   CSV EXPORT (ALL / SINGLE SECTION)
========================= */
if ($export && $courseid) {

    header('Content-Type: text/csv');
    $filename = $sectionid >= 0 ? "quiz_report_course_{$courseid}_section_{$sectionid}.csv"
                                 : "quiz_report_course_{$courseid}_all_sections.csv";
    header('Content-Disposition: attachment; filename="'.$filename.'"');

    $out = fopen('php://output', 'w');

    fputcsv($out, ['Username','Email','Section','Quiz','Grade (%)','Status','Attendance']);

    foreach ($users as $u) {
        foreach ($modinfo->get_cms() as $cm) {

            if (!$cm->uservisible || $cm->deletioninprogress || $cm->modname != 'quiz') {
                continue;
            }

            if ($sectionid >= 0 && $cm->sectionnum != $sectionid) {
                continue; // skip other sections
            }

            $quiz = $DB->get_record('quiz', ['id'=>$cm->instance], '*', MUST_EXIST);
           $attempt = $DB->get_record_sql(
    "SELECT *
       FROM {quiz_attempts}
      WHERE quiz = :quiz
        AND userid = :userid
      ORDER BY attempt DESC",
    [
        'quiz'   => $quiz->id,
        'userid' => $u->id
    ],
    IGNORE_MULTIPLE
);

            $grade   = $DB->get_record('quiz_grades', ['quiz'=>$quiz->id,'userid'=>$u->id]);

            if ($attempt) {
                if ($attempt->state === 'finished') {
                    $status = 'Completed';
                    $attendance = 'Present';
                } else {
                    $status = 'In Progress';
                    $attendance = '--';
                }
            } else {
                $status = 'Not Started';
                $attendance = 'Absent';
            }

           $gradepercent = '--';

if ($grade && $quiz->grade > 0) {
    $gradepercent = round(($grade->grade / $quiz->grade) * 100, 2) . '%';
}


            $sectionname = local_get_section_name($courseid, $cm->sectionnum);

            fputcsv($out, [
                fullname($u),
                $u->email,
                $sectionname,
                $quiz->name,
                $gradepercent,
                $status,
                $attendance
            ]);
        }
    }

    fclose($out);
    exit;
}

/* =========================
   PAGE SETUP
========================= */
$PAGE->set_url('/local/incourse/quiz_report.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Quiz Report');
// echo $OUTPUT->header();
?>

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style>
    .userinitials {width: 35px;height: 35px;}
    </style>
<script src="https://cdn.tailwindcss.com"></script>

<div class="max-w-7xl mx-auto mt-6 space-y-4" style="    padding: 10px;">

<h1 class="text-2xl font-bold flex items-center gap-2 " style="display:none;">
    <span class="material-icons text-indigo-600">insights</span>
    Quiz Activity Report
</h1>

<form method="get" class="flex flex-wrap items-center gap-4 bg-white p-4 border rounded-lg">

    <!-- Course -->
  <select name="courseid"
        class="border rounded px-3 py-2 w-80"
        onchange="this.form.submit()">

    <option value="">-- Select Course --</option>

    <?php foreach ($teacher_courses as $id => $name): ?>
        <option value="<?= $id ?>" <?= ($id == $courseid) ? 'selected' : '' ?>>
            <?= format_string($name) ?>
        </option>
    <?php endforeach; ?>

</select>


<?php if ($courseid): ?>

    <!-- Section -->
    <select name="sectionid" class="border rounded px-3 py-2 w-72" onchange="this.form.submit()">
        <option value="-1" <?= ($sectionid==-1)?'selected':'' ?>>All Sections</option>
        <?php
        $sections = $DB->get_records('course_sections', ['course'=>$courseid],'section ASC','section,name');
        foreach ($sections as $s):
            if ($s->section == 0) continue;
        ?>
            <option value="<?= $s->section ?>" <?= ($sectionid==$s->section)?'selected':'' ?>>
                <?= $s->name ?: 'Section '.$s->section ?>
            </option>
        <?php endforeach; ?>
    </select>

    <!-- Export button -->
    <a href="?courseid=<?= $courseid ?>&sectionid=<?= $sectionid ?>&export=1"
       class="ml-auto bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center gap-1">
        <span class="material-icons text-sm">download</span>
        Export CSV
    </a>

<?php endif; ?>
</form>

<?php if ($courseid): ?>

<div class="overflow-x-auto border rounded-lg bg-white mt-4">
<table class="min-w-full text-sm divide-y m-0">
<thead class="bg-gray-100">
<tr>
<th class="px-3 py-2">User</th>
<th>Email</th>
<th>Section</th>
<th>Quiz</th>
<th class="text-center">Grade</th>
<th>Status</th>
<th>Attendance</th>
</tr>
</thead>
<tbody id="quizTableBody" class="divide-y">


<?php
foreach ($users as $u) {
    foreach ($modinfo->get_cms() as $cm) {

        if (!$cm->uservisible || $cm->deletioninprogress || $cm->modname != 'quiz') {
            continue;
        }

        if ($sectionid >= 0 && $cm->sectionnum != $sectionid) {
            continue;
        }

        $quiz = $DB->get_record('quiz', ['id'=>$cm->instance], '*', MUST_EXIST);
        $attempt = $DB->get_record_sql(
    "SELECT *
       FROM {quiz_attempts}
      WHERE quiz = :quiz
        AND userid = :userid
      ORDER BY attempt DESC",
    [
        'quiz'   => $quiz->id,
        'userid' => $u->id
    ],
    IGNORE_MULTIPLE
);

        $grade   = $DB->get_record('quiz_grades', ['quiz'=>$quiz->id,'userid'=>$u->id]);

        if ($attempt) {
            if ($attempt->state === 'finished') {
                $status = '<span class="text-green-600 flex gap-1"><span class="material-icons text-sm">check_circle</span>Completed</span>';
                $attendance = 'Present';
            } else {
                $status = '<span class="text-yellow-600 flex gap-1"><span class="material-icons text-sm">autorenew</span>In Progress</span>';
                $attendance = '--';
            }
        } else {
            $status = '<span class="text-red-600 flex gap-1"><span class="material-icons text-sm">cancel</span>Not Started</span>';
            $attendance = 'Absent';
        }

        $gradepercent = ($grade && $quiz->grade > 0)
    ? floor(($grade->grade / $quiz->grade) * 100) . '%'
    : '--';

        $sectionname  = local_get_section_name($courseid, $cm->sectionnum);
        $highestgrade = local_get_highest_quiz_grade($quiz->id);

$isHighest = false;
if ($grade && $highestgrade !== null && (float)$grade->grade === (float)$highestgrade) {
    $isHighest = true;
}

?>
<tr>
<td class="px-3 py-2 flex items-center gap-2"><?= $OUTPUT->user_picture($u, ['size'=>35]) ?> <?= fullname($u) ?></td>
<td><?= s($u->email) ?></td>
<td><?= format_string($sectionname) ?></td>
<td><?= format_string($quiz->name) ?></td>
<td class="text-center">
    <?php if ($gradepercent !== '--'): ?>
        <span class="<?= $isHighest ? 'font-bold text-green-600' : '' ?>">
            <?= $gradepercent ?>
        </span>

        <?php if ($isHighest): ?>
        <?php endif; ?>
    <?php else: ?>
        --
    <?php endif; ?>
</td>

<td><?= $status ?></td>
<td><?= $attendance ?></td>
</tr>
<?php
    }
}
?>

</tbody>
</table>
<div class="flex justify-end items-center gap-2 mt-4">
    <button id="prevBtn"
        class="px-3 py-1 border rounded bg-gray-100 hover:bg-gray-200">
        Prev
    </button>

    <span id="pageInfo" class="text-sm text-gray-600"></span>

    <button id="nextBtn"
        class="px-3 py-1 border rounded bg-gray-100 hover:bg-gray-200">
        Next
    </button>
</div>

</div>

<?php endif; ?>

</div>
<script>

document.addEventListener("DOMContentLoaded", function () {
    const rowsPerPage = 6;
    const tbody = document.getElementById("quizTableBody");
    const rows = Array.from(tbody.querySelectorAll("tr"));

    let currentPage = 1;
    const totalPages = Math.ceil(rows.length / rowsPerPage);

    const prevBtn = document.getElementById("prevBtn");
    const nextBtn = document.getElementById("nextBtn");
    const pageInfo = document.getElementById("pageInfo");

    function renderPage() {
        rows.forEach((row, index) => {
            row.style.display =
                index >= (currentPage - 1) * rowsPerPage &&
                index < currentPage * rowsPerPage
                    ? ""
                    : "none";
        });

        pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
        prevBtn.disabled = currentPage === 1;
        nextBtn.disabled = currentPage === totalPages;
    }

    prevBtn.addEventListener("click", () => {
        if (currentPage > 1) {
            currentPage--;
            renderPage();
        }
    });

    nextBtn.addEventListener("click", () => {
        if (currentPage < totalPages) {
            currentPage++;
            renderPage();
        }
    });

    renderPage(); // initial load
});
    </script>

<?php 
// echo $OUTPUT->footer();
 ?> 
