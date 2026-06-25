<?php 
require_once("../../config.php");
require_login();
$isadmin = is_siteadmin();

global $DB, $USER, $CFG, $OUTPUT;
$embedded = optional_param('embedded', 0, PARAM_BOOL);
if ($embedded) {
    $PAGE->set_pagelayout('embedded');
}

// --------------------
// COURSE SELECTION HANDLING
// --------------------
$page     = optional_param('page', 0, PARAM_INT);
$perpage  = 5; // forums per page
$offset   = $page * $perpage;

$courseid = optional_param('courseid', 0, PARAM_INT);

// Always set page URL
$PAGE->set_url(new moodle_url('/local/incourse/forum_grade.php', ['courseid'=>$courseid]));
$PAGE->set_pagelayout('standard');

?>
<style>
    #page-local-incourse-forum_grade div[role=main] {
      height: 100% !important;
}
</style>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<?php
// echo $OUTPUT->header();

// Fetch all courses for dropdown


// Check if user is teacher or admin
$isteacheroradmin = is_siteadmin() || has_capability('moodle/course:update', context_system::instance());

$teachingcourses = [];

if ($isadmin) {
    // ADMIN → all courses
    $allcourses = $DB->get_records('course', null, 'fullname ASC', 'id, fullname');
    foreach ($allcourses as $c) {
        $teachingcourses[$c->id] = $c;
    }
} else {
    // TEACHER → only editable courses
    $enrolled = enrol_get_users_courses($USER->id, true, ['id', 'fullname']);

    foreach ($enrolled as $c) {
        $ctx = context_course::instance($c->id);
        if (has_capability('moodle/course:update', $ctx)) {
            $teachingcourses[$c->id] = $c;
        }
    }
}


// Dropdown
echo '<div class="max-w-5xl mx-auto pt-4 pb-4  d-flex">
        <label class="flex items-center gap-2 font-medium text-gray-700 text-lg">
            <span class="material-icons text-blue-500">school</span>
            Select Course:
        </label>
        <form method="GET" class="mt-2 ml-2">
            <select name="courseid" id="courseid" class="border p-2 rounded w-80" onchange="this.form.submit()">
                <option value="">-- Select Course --</option>';

foreach ($teachingcourses as $course) {
    $selected = ($courseid == $course->id) ? "selected" : "";
    echo "<option value='{$course->id}' $selected>" .
         format_string($course->fullname) .
         "</option>";
}


echo '      </select>
        </form>
      </div>';

// Stop if no course selected
if ($courseid == 0) {
    echo '<div class="flex flex-col items-center justify-center h-96 text-gray-500">
            <span class="material-icons text-6xl mb-4 text-blue-500">school</span>
            <h2 class="text-xl font-semibold mb-2">Please select a course</h2>
            <p class="text-sm">Choose a course from the dropdown above to view and manage forum grades.</p>
          </div>';
    exit;
}
// --------------------
// COURSE SELECTED → LOAD REPORT LOGIC
// --------------------
$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
$context = context_course::instance($courseid);

if (!$isadmin && !has_capability('moodle/course:update', $context)) {
    print_error('nopermissions', 'error', '', 'access this course');
}


$PAGE->set_title("Forum Grading Report - " . $course->fullname);
$PAGE->set_heading($course->fullname);
?>



<h1 class="text-2xl font-bold mb-6 max-w-5xl mx-auto flex items-center gap-2">
    <span class="material-icons text-blue-500">forum</span>
    Forum Responses Report - <?php echo $course->fullname; ?>
</h1>

<?php
$totalforums = $DB->count_records_sql("
    SELECT COUNT(1)
    FROM {forum} f
    JOIN {course_modules} cm ON cm.instance = f.id
    WHERE cm.course = :courseid
      AND f.type <> 'news'
      AND cm.deletioninprogress = 0
      AND cm.visible = 1
", ['courseid' => $courseid]);

$forums = $DB->get_records_sql("
    SELECT f.id AS forumid, f.name AS forumname, cm.section
    FROM {forum} f
    JOIN {course_modules} cm ON cm.instance = f.id
    WHERE cm.course = :courseid
      AND f.type <> 'news'
      AND cm.deletioninprogress = 0
      AND cm.visible = 1
    ORDER BY cm.section, f.name
    LIMIT {$perpage} OFFSET {$offset}
", ['courseid' => $courseid]);


// Fetch forums (excluding announcements)
// $forums = $DB->get_records_sql("
//  SELECT f.id AS forumid, f.name AS forumname, cm.section
// FROM {forum} f
// JOIN {course_modules} cm ON cm.instance = f.id
// WHERE cm.course = :courseid
//   AND f.type <> 'news'
//   AND cm.deletioninprogress = 0       -- hide deleted forums
//   AND cm.visible = 1                  -- hide hidden forums
// ORDER BY cm.section, f.name

// ", ['courseid' => $courseid]);

if (empty($forums)) {
    // No forums message
    echo '<div class="max-w-5xl mx-auto p-6 my-6 text-center text-gray-600 border border-dashed border-gray-300 rounded-lg bg-gray-50 flex flex-col items-center gap-3">
            <span class="material-icons text-5xl text-gray-400">forum</span>
            <h2 class="text-xl font-semibold">No forums available</h2>
            <p class="text-gray-500">There are no forums (other than announcements) in this course to display.</p>
          </div>';
} else {
    // Table header
    echo '<div class="max-w-5xl mx-auto overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border border-gray-300 mb-6">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Section / Forum</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Question</th>
                    <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">Responses</th>
                </tr>
            </thead>
            <tbody>';
    
    foreach ($forums as $forum) {
        $question = $DB->get_field_sql("
            SELECT name FROM {forum_discussions}
            WHERE forum = ?
            ORDER BY id ASC LIMIT 1
        ", [$forum->forumid]) ?? "N/A";

        $responsecount = $DB->count_records_sql("
            SELECT COUNT(fp.id)
            FROM {forum_posts} fp
            JOIN {forum_discussions} fd ON fd.id = fp.discussion
            WHERE fd.forum = :forumid AND fp.deleted = 0
        ", ['forumid' => $forum->forumid]);

        echo "
            <tr class='hover:bg-gray-50 cursor-pointer' onclick='openModal({$forum->forumid})'>
                <td class='px-4 py-2'><span class='material-icons align-middle text-blue-500'>forum</span> {$forum->forumname}</td>
                <td class='px-4 py-2'>{$question}</td>
                <td class='px-4 py-2 text-center font-semibold'>{$responsecount}</td>
            </tr>";
    }

    echo '</tbody></table></div>';
}
?>
<?php if ($totalforums > $perpage): 
    $totalpages = ceil($totalforums / $perpage); ?>
    <div class="max-w-5xl mx-auto mt-4 flex justify-end space-x-2">
        <?php for ($i = 0; $i < $totalpages; $i++): ?>
            <a href="?courseid=<?= $courseid ?>&page=<?= $i ?>"
               class="px-3 py-1 border rounded 
               <?= $i == $page ? 'bg-blue-600 text-white' : 'bg-white text-blue-600' ?>">
                <?= $i + 1 ?>
            </a>
        <?php endfor; ?>
    </div>
<?php endif; ?>


<!-- Modal -->
<div id="forumModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-40 items-center justify-center" style="z-index: 99999999999999;">
    <div class="bg-white rounded-lg w-11/12 max-w-5xl p-6 relative mx-auto my-20 shadow-lg">
        <button onclick="closeModal()" class="absolute top-2 right-2 material-icons text-gray-700 hover:text-black cursor-pointer text-2xl">
            close
        </button>

        <!-- Title + Downloads -->
           <!-- Question + Download Buttons -->
        <div class="flex justify-between items-center mb-4" style="    width: 98%;">
            <h2 class="text-xl font-bold flex items-center gap-2" id="modalTitle">
                <span class="material-icons text-blue-500">question_answer</span> 
            </h2>
            <div class="flex gap-2">
                <button id="downloadPdf" class="bg-red-600 d-none text-white px-3 py-1 rounded flex items-center gap-1 hover:bg-red-700">
                    <span class="material-icons">picture_as_pdf</span> PDF
                </button>
                <button id="downloadExcel" class="bg-green-600 text-white px-3 py-1 rounded flex items-center gap-1 hover:bg-green-700">
    <span class="material-icons">file_download</span> Excel
</button>

            </div>
        </div>
       

        <!-- Search bar -->
      <div class="mb-3 relative col-md-6">
    <span class="material-icons absolute  left-3 top-2.5 ml-2 text-gray-500">
        search
    </span>
    <input type="text" 
           id="searchInput" 
           onkeyup="applySearch()" 
           placeholder="Search user, email or text..."
           class="border rounded pl-10 px-3  py-2 w-full focus:ring focus:ring-blue-200" style="padding-left: 40px !important;
">
</div>


        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-2 py-2 text-center text-sm font-medium text-gray-700">#</th>
                        <th class="px-2 py-2 text-center text-sm font-medium text-gray-700">Pic</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700"> Name</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Email</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Response</th>
                        <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">Grade</th>
                    </tr>
                </thead>
                <tbody id="modalBody" class="text-sm text-gray-700"></tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-between items-center mt-4">
            <button onclick="prevPage()"
                class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Prev</button>
            <span id="pageInfo" class="text-sm font-semibold"></span>
            <button onclick="nextPage()"
                class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Next</button>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let currentForumId = 0;
let fullData = [];  // All data from AJAX
let filteredData = []; 
let currentPage = 1;
const itemsPerPage = 4;

// ------------------------------
// OPEN MODAL + LOAD DATA
// ------------------------------
function openModal(forumid) {
    currentForumId = forumid;
    $('#forumModal').removeClass('hidden').addClass('flex');
    $('#modalBody').html('<tr><td colspan="6" class="px-4 py-2 text-center">Loading...</td></tr>');

    $.ajax({
        url: 'forum_grade_ajax.php',
        method: 'GET',
        data: { forumid: forumid },
        dataType: 'json',
        success: function(res) {
            fullData = res;
            filteredData = res;
            currentPage = 1;

            if(res.length === 0) {
                $('#modalBody').html('<tr><td colspan="6" class="text-center text-gray-500 py-3">No responses found</td></tr>');
                return;
            }

            $('#modalTitle').html('<span class="material-icons text-blue-500">question_answer</span> ' + res[0].question);

            renderTable();
        },
        error: function() {
            $('#modalBody').html('<tr><td colspan="6" class="text-center text-red-600">Error loading responses</td></tr>');
        }
    });
}

// ------------------------------
// SEARCH FUNCTION
// ------------------------------
function applySearch() {
    let q = $('#searchInput').val().toLowerCase();

    filteredData = fullData.filter(r =>
        r.student.toLowerCase().includes(q) ||
        r.email.toLowerCase().includes(q) ||
        r.response.toLowerCase().includes(q)
    );

    currentPage = 1;
    renderTable();
}

// ------------------------------
// PAGINATION
// ------------------------------
function renderTable() {
    $('#modalBody').empty();

    let start = (currentPage - 1) * itemsPerPage;
    let end = start + itemsPerPage;
    let pageItems = filteredData.slice(start, end);

    if(pageItems.length === 0) {
        $('#modalBody').html('<tr><td colspan="6" class="text-center py-3 text-gray-500">No results found</td></tr>');
    }

    pageItems.forEach(function(r, index) {
        $('#modalBody').append(`
            <tr class="border-b">
                <td class="px-2 py-2 text-center">${start + index + 1}</td>
                <td class="px-2 py-2 text-center">${r.picture}</td>
                <td class="px-4 py-2">${r.student}</td>
                <td class="px-4 py-2">${r.email}</td>
                <td class="px-4 py-2">${r.response}</td>
                <td class="px-4 py-2 text-center font-semibold">${r.grade}</td>
            </tr>
        `);
    });

    // Page Info
    let totalPages = Math.ceil(filteredData.length / itemsPerPage);
    $('#pageInfo').text(`Page ${currentPage} of ${totalPages}`);
}

function nextPage() {
    let totalPages = Math.ceil(filteredData.length / itemsPerPage);
    if(currentPage < totalPages) {
        currentPage++;
        renderTable();
    }
}

function prevPage() {
    if(currentPage > 1) {
        currentPage--;
        renderTable();
    }
}

// ------------------------------
// CLOSE MODAL
// ------------------------------
function closeModal() {
    $('#forumModal').addClass('hidden').removeClass('flex');
}

// Download buttons
$('#downloadPdf').click(function() {
    window.location = 'forum_grade_ajax.php?forumid=' + currentForumId + '&download=pdf';
});
$('#downloadExcel').click(function() {
    window.location = 'forum_grade_ajax.php?forumid=' + currentForumId + '&download=xlsx';
});
</script>

<?php
//  echo $OUTPUT->footer(); 
 ?>
