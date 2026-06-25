<?php
require_once(__DIR__ . '/../config.php');
require_login();

global $DB, $USER, $CFG;

// Page setup
$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/my/courses.php'));
$PAGE->set_pagelayout('standard');
$PAGE->set_title("My Courses");

// Get enrolled courses
$courses = enrol_get_users_courses($USER->id, true, '*');

// To fetch course images
require_once($CFG->libdir . '/coursecatlib.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Courses</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .course-card { border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: transform .2s; }
    .course-card:hover { transform: translateY(-5px); }
    .course-img { height: 160px; object-fit: cover; width: 100%; }
    .course-body { padding: 15px; }
    .course-code { font-size: 0.9rem; color: #666; }
  </style>
</head>
<body>

<?php include(__DIR__ . '/custom_header.php'); ?>

<main class="container my-4">
  <h2 class="mb-4">My Enrolled Courses</h2>
  <div class="row g-4">
    <?php if (!empty($courses)) : ?>
      <?php foreach ($courses as $course) :
          // Get course image from course summary files
          $context = context_course::instance($course->id);
          $fs = get_file_storage();
          $files = $fs->get_area_files($context->id, 'course', 'overviewfiles', false, 'itemid, filepath, filename', false);
          $imgurl = $CFG->wwwroot . '/theme/image.php/boost/core/1676986254/f/course'; // default fallback
          if ($files) {
              $file = reset($files);
              $imgurl = moodle_url::make_pluginfile_url($file->get_contextid(),
                  $file->get_component(),
                  $file->get_filearea(),
                  $file->get_itemid(),
                  $file->get_filepath(),
                  $file->get_filename())->out();
          }
      ?>
        <div class="col-md-4">
          <div class="card course-card">
            <img src="<?php echo $imgurl; ?>" alt="Course Image" class="course-img">
            <div class="course-body">
              <h5 class="card-title"><?php echo format_string($course->fullname); ?></h5>
              <p class="course-code">Code: <?php echo $course->shortname; ?></p>
              <a href="<?php echo $CFG->wwwroot . '/course/view.php?id=' . $course->id; ?>" class="btn btn-primary btn-sm">Go to Course</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>You are not enrolled in any courses.</p>
    <?php endif; ?>
  </div>
</main>

<?php include(__DIR__ . '/custom_footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
