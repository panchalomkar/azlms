<?php
namespace local_courses;

defined('MOODLE_INTERNAL') || die();

class course_helper {

    /**
     * Get all enrolled courses for user with progress & status info.
     */
    public function get_user_courses(int $userid, string $filter = 'all'): array {
        global $DB, $CFG;
        require_once($CFG->dirroot . '/lib/completionlib.php');
        require_once($CFG->dirroot . '/course/lib.php');

        $courses = enrol_get_users_courses($userid, true, null, 'fullname ASC');
        $result  = [];

        foreach ($courses as $course) {
            if ($course->id == SITEID) continue;

            $context    = \context_course::instance($course->id);
            $completion = new \completion_info($course);

            // ── Progress calculation ──
            $progress   = 0;
            $status     = 'notstarted'; // default

            if ($completion->is_enabled()) {
                $activities = $completion->get_activities();
                $total      = count($activities);

                if ($total > 0) {
                    $done = 0;
                    foreach ($activities as $activity) {
                        $data = $completion->get_data($activity, false, $userid);
                        if (in_array($data->completionstate, [
                            COMPLETION_COMPLETE,
                            COMPLETION_COMPLETE_PASS,
                        ])) {
                            $done++;
                        }
                    }
                    $progress = round(($done / $total) * 100);
                }

                // Course-level completion
                $ccompletion = new \completion_completion(['userid' => $userid, 'course' => $course->id]);
                if ($ccompletion->is_complete()) {
                    $status   = 'completed';
                    $progress = 100;
                } elseif ($progress > 0) {
                    $status = 'ongoing';
                } else {
                    $status = 'notstarted';
                }
            }

            // Apply filter
            if ($filter !== 'all' && $status !== $filter) continue;

            // ── Instructor ──
            $instructors = get_users_by_capability($context, 'moodle/course:update', 'u.id, u.firstname, u.lastname');
            $instructor  = 'N/A';
            if (!empty($instructors)) {
                $ins        = reset($instructors);
                $instructor = fullname($ins);
            }

            // ── Lesson count ──
            $lessoncount = $DB->count_records('course_modules', [
                'course'   => $course->id,
                'deletioninprogress' => 0,
            ]);

            // ── Duration (sum of module time limits or default) ──
            $duration = '13 hr 35 min'; // placeholder — replace with your logic

            // ── Build segment bars for progress (like the UI shows) ──
            $segments     = 30;
            $filled       = (int) round($segments * $progress / 100);
            $segmentbars  = [];
            for ($i = 0; $i < $segments; $i++) {
                $segmentbars[] = ['filled' => $i < $filled];
            }

            // ── Status badge colours ──
            $badgeclass = match($status) {
                'ongoing'    => 'badge-ongoing',
                'notstarted' => 'badge-notstarted',
                'completed'  => 'badge-completed',
                default      => '',
            };

            $badgelabel = match($status) {
                'ongoing'    => 'Ongoing',
                'notstarted' => 'Not started',
                'completed'  => 'Completed',
                default      => '',
            };

            // ── View URL ──
            $viewurl = new \moodle_url('/course/view.php', ['id' => $course->id]);

            // ── Completion score (for popup) ──
            $finalscore   = $this->get_course_grade($userid, $course->id);
            $quizaccuracy = $this->get_quiz_accuracy($userid, $course->id);

            $result[] = [
                'id'           => $course->id,
                'fullname'     => $course->fullname,
                'instructor'   => $instructor,
                'lessoncount'  => $lessoncount ?: 15,
                'duration'     => $duration,
                'progress'     => $progress,
                'status'       => $status,
                'badgeclass'   => $badgeclass,
                'badgelabel'   => $badgelabel,
                'viewurl'      => $viewurl->out(false),
                'ongoing'      => $status === 'ongoing',
                'notstarted'   => $status === 'notstarted',
                'completed'    => $status === 'completed',
                'segmentbars'  => $segmentbars,
                'finalscore'   => $finalscore,
                'quizaccuracy' => $quizaccuracy,
                'username'     => fullname(\core_user::get_user($userid)),
            ];
        }

        return $result;
    }

    /**
     * Get final grade percentage for a course.
     */
    private function get_course_grade(int $userid, int $courseid): int {
        global $CFG;
        require_once($CFG->dirroot . '/lib/gradelib.php');

        $grade_item = \grade_item::fetch_course_item($courseid);
        if (!$grade_item) return 0;

        $grade = new \grade_grade(['itemid' => $grade_item->id, 'userid' => $userid]);
        $grade->grade_item = $grade_item;

        $min = $grade_item->grademin;
        $max = $grade_item->grademax;
        if ($max - $min == 0) return 0;

        $finalgrade = $grade->finalgrade ?? 0;
        return (int) round((($finalgrade - $min) / ($max - $min)) * 100);
    }

    /**
     * Get average quiz accuracy across all quizzes in the course.
     */
    private function get_quiz_accuracy(int $userid, int $courseid): int {
        global $DB;

        $quizzes = $DB->get_records('quiz', ['course' => $courseid]);
        if (empty($quizzes)) return 0;

        $total = 0; $count = 0;
        foreach ($quizzes as $quiz) {
            $attempt = $DB->get_record_sql(
                "SELECT sumgrades, quiz FROM {quiz_attempts}
                  WHERE quiz = :qid AND userid = :uid AND state = 'finished'
                  ORDER BY sumgrades DESC LIMIT 1",
                ['qid' => $quiz->id, 'uid' => $userid]
            );
            if ($attempt && $quiz->sumgrades > 0) {
                $total += ($attempt->sumgrades / $quiz->sumgrades) * 100;
                $count++;
            }
        }

        return $count > 0 ? (int) round($total / $count) : 0;
    }

    /**
     * Get improvement suggestions (static for now — wire up AI/LLM later).
     */
    public function get_improvement_suggestions(int $userid, int $courseid): array {
        return [
            [
                'title'       => 'Improve Research Thinking',
                'tag'         => '72% mastery',
                'tagclass'    => 'tag-mastery',
                'description' => 'Spend more time validating problem statements and user behavior analysis.',
            ],
            [
                'title'       => 'Increase Submission Speed',
                'tag'         => 'Time efficiency',
                'tagclass'    => 'tag-efficiency',
                'description' => 'You performed strongly but took longer than average learners.',
            ],
            [
                'title'       => 'Advanced UX Case Studies',
                'tag'         => 'Recommended',
                'tagclass'    => 'tag-recommended',
                'description' => 'Recommended for stronger product strategy and execution skills.',
            ],
        ];
    }
}
