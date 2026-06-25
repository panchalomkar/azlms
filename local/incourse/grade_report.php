<?php
require_once(__DIR__ . '/../../config.php');
require_login();
$embedded = optional_param('embedded', 0, PARAM_BOOL);

if ($embedded) {
    $PAGE->set_pagelayout('embedded');
}

global $DB, $CFG, $PAGE, $OUTPUT, $USER;

// ---------------- PERMISSIONS ----------------
// Admins can see all courses
$isadmin = is_siteadmin();

$teachingcourses = [];

if ($isadmin) {
    // Admin → all courses except site course
    $teachingcourses = $DB->get_records_sql("
        SELECT id, fullname
        FROM {course}
        WHERE id <> 1
        ORDER BY fullname
    ");
} else {
    // Teacher → only editable courses
    $allcourses = enrol_get_users_courses($USER->id, true);

    foreach ($allcourses as $course) {
        $context = context_course::instance($course->id);
        if (has_capability('moodle/course:update', $context)) {
            $teachingcourses[] = $course;
        }
    }
}


// If not admin and no courses assigned, block access
if (!$isadmin && empty($teachingcourses)) {
    throw new moodle_exception('nopermissions', 'error', '', 'You are not assigned to any courses.');
}

// ---------------- PAGE SETUP ----------------
$PAGE->set_url('/local/incourse/grade_report.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Custom Grade Report');
$PAGE->set_heading('Custom Grade Report');

/* ---------------- SAVE / UPDATE ---------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && confirm_sesskey()) {

    $id       = optional_param('id', 0, PARAM_INT);
    $courseid = required_param('courseid', PARAM_INT);
    $userid   = required_param('userid', PARAM_INT);
    $examname = required_param('examname', PARAM_TEXT);
    $grade    = required_param('grade', PARAM_TEXT);

    // Verify user has permission in this course
    $context = context_course::instance($courseid);
    if (!is_siteadmin() && !has_capability('moodle/course:update', $context)) {
        throw new moodle_exception('nopermissions');
    }

    $filename = null;
    $filepath = null;

    if (!empty($_FILES['gradefile']['name'])) {
        $allowed = ['pdf','doc','docx'];
        $ext = strtolower(pathinfo($_FILES['gradefile']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            throw new moodle_exception('Invalid file type');
        }

        $dir = $CFG->dataroot . '/incourse_grades/';
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        $filename = time() . '_' . clean_param($_FILES['gradefile']['name'], PARAM_FILE);
        $filepath = $dir . $filename;
        move_uploaded_file($_FILES['gradefile']['tmp_name'], $filepath);
    }

    $record = new stdClass();
    $record->courseid = $courseid;
    $record->userid = $userid;
    $record->examname = $examname;
    $record->grade = $grade;
    $record->timemodified = time();

    if ($filename) {
        $record->filename = $filename;
        $record->filepath = $filepath;
    }

    if ($id) {
        $record->id = $id;
        $DB->update_record('local_incourse_grades', $record);
    } else {
        $record->timecreated = time();
        $DB->insert_record('local_incourse_grades', $record);
    }

    redirect(
        new moodle_url('/local/incourse/grade_report.php', ['courseid' => $courseid]),
        'Saved successfully'
    );
}

/* ---------------- DATA ---------------- */
$courses  = $teachingcourses; // Only courses teacher/admin can see
$courseid = optional_param('courseid', 0, PARAM_INT);

// Pagination
$page     = optional_param('page', 0, PARAM_INT);
$perpage  = 8;
$offset   = $page * $perpage;

$users = [];
$totalusers = 0;

if ($courseid) {
    $context = context_course::instance($courseid);
    if (!is_siteadmin() && !has_capability('moodle/course:update', $context)) {
        throw new moodle_exception('nopermissions');
    }

    $allusers   = get_enrolled_users($context);
    $totalusers = count($allusers);
    $users      = array_slice($allusers, $offset, $perpage);
}?>

<!-- Tailwind + Material Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<script>
window.openModal = function(data, userid) {
    document.getElementById('modal').classList.remove('hidden');
    document.getElementById('modal').classList.add('flex');

    document.getElementById('uid').value = userid;
    document.getElementById('gid').value = data?.id || '';
    document.getElementById('exam').value = data?.examname || '';
    document.getElementById('grade').value = data?.grade || '';

    const fileBox = document.getElementById('existingfile');
    const fileLink = document.getElementById('existingfilelink');

    if (data && data.filename) {
        fileBox.classList.remove('hidden');
        fileLink.textContent = data.filename;
        fileLink.href = "<?= $CFG->wwwroot ?>/local/incourse/view.php?file=" + encodeURIComponent(data.filename);
    } else {
        fileBox.classList.add('hidden');
        fileLink.textContent = '';
        fileLink.href = '';
    }
};

window.closeModal = function() {
    document.getElementById('modal').classList.add('hidden');
};
</script>

<div class="max-w-7xl mx-auto p-6 bg-white rounded">

    <!-- COURSE SELECT -->
    <form method="get" class="mb-6">
        <select name="courseid" onchange="this.form.submit()"
                class="border px-3 py-2 rounded w-1/2">
            <option value="">Select Course</option>
            <?php foreach ($courses as $c): ?>
                <option value="<?= $c->id ?>" <?= $c->id == $courseid ? 'selected' : '' ?>>
                    <?= format_string($c->fullname) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
<?php if (!$courseid): ?>
    <div class="flex flex-col items-center justify-center h-96 text-gray-500">
        <span class="material-icons text-6xl mb-4 text-blue-500">
            school
        </span>
        <h2 class="text-xl font-semibold mb-2">
            Please select a course
        </h2>
        <p class="text-sm">
            Choose a course from the dropdown above to view and manage grades.
        </p>
    </div>
<?php endif; ?>

    <?php if ($courseid && $users): ?>
        <table class="min-w-full border bg-white">
            <thead class="bg-gray-100">
            <tr>
                <th class="border p-2">User</th>
                <th class="border p-2">Exam</th>
                <th class="border p-2">Grade</th>
                <th class="border p-2">File</th>
                <th class="border p-2">Action</th>
            </tr>
            </thead>
            <tbody>

            <?php foreach ($users as $u):
                $grade = $DB->get_record('local_incourse_grades', [
                    'courseid' => $courseid,
                    'userid'   => $u->id
                ]);
            ?>
                <tr>
                    <td class="border p-2 flex gap-2 items-center">
                        <?= $OUTPUT->user_picture($u, ['size'=>35]) ?>
                        <?= fullname($u) ?>
                    </td>

                    <td class="border p-2"><?= $grade->examname ?? '-' ?></td>
                    <td class="border p-2"><?= $grade->grade ?? '-' ?></td>

                    <td class="border p-2">
                        <?php if (!empty($grade->filename)): ?>
                            <div class="flex gap-3">
                                <a target="_blank"
                                   href="<?= $CFG->wwwroot ?>/local/incourse/view.php?file=<?= urlencode($grade->filename) ?>"
                                   class="material-icons text-green-600">visibility</a>

                                <a href="<?= $CFG->wwwroot ?>/local/incourse/download.php?file=<?= urlencode($grade->filename) ?>"
                                   class="material-icons text-blue-600">download</a>
                            </div>
                        <?php else: ?>-<?php endif; ?>
                    </td>

                    <td class="border p-2">
                        <button onclick='openModal(<?= json_encode($grade ?: new stdClass()) ?>, <?= (int)$u->id ?>)'
                                class="material-icons text-blue-600">
                            <?= $grade ? 'edit' : 'add' ?>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>

            </tbody>
        </table>

        <!-- PAGINATION (Bottom Right – DataTable style) -->
        <div class="mt-4 flex justify-end">
           <?php if ($totalusers > $perpage): 
    $totalpages = ceil($totalusers / $perpage); ?>
    <div class="mt-4 flex justify-end space-x-2">
        <?php for ($i = 0; $i < $totalpages; $i++): ?>
            <a href="?courseid=<?= $courseid ?>&page=<?= $i ?>"
               class="px-3 py-1 border rounded <?= $i == $page ? 'bg-blue-600 text-white' : 'bg-white text-blue-600' ?>">
               <?= $i + 1 ?>
            </a>
        <?php endfor; ?>
    </div>
<?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<!-- MODAL (unchanged) -->
<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
    <form method="post" enctype="multipart/form-data"
          class="bg-white p-6 rounded w-96">

        <input type="hidden" name="sesskey" value="<?= sesskey() ?>">
        <input type="hidden" name="id" id="gid">
        <input type="hidden" name="courseid" value="<?= $courseid ?>">
        <input type="hidden" name="userid" id="uid">
        <label for="">Exam Name</label>
        <input id="exam" name="examname" class="border p-2 w-full mb-3"
               placeholder="Exam Name" required>
<label for="">Grade</label>
        <input id="grade" name="grade" class="border p-2 w-full mb-3"
               placeholder="Grade" required>
<label for="">Upload file</label>
        <div id="existingfile" class="mb-3 text-sm hidden">
            <span class="font-semibold">Existing file:</span>
            <a id="existingfilelink" target="_blank"
               class="text-blue-600 underline ml-1"></a>
        </div>

        <input type="file" name="gradefile"
               accept=".pdf,.doc,.docx" class="mb-3">

        <div class="flex justify-end gap-2">
            <button type="button" onclick="closeModal()" class="border px-3 py-1">Cancel</button>
            <button class="bg-blue-600 text-white px-3 py-1">Save</button>
        </div>
    </form>
</div>

<?php 
// echo $OUTPUT->footer();
 ?>

