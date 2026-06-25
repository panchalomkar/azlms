<?php
require_once('config.php');

$PAGE->set_url(new moodle_url('/local/yourplugin/thankyou.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('thankyoutitle', 'local_yourplugin'));
$PAGE->set_heading(get_string('thankyouheading', 'local_yourplugin'));

echo $OUTPUT->header();
?>

<div class="thankyou-message" style="text-align:center; margin:50px auto; max-width:600px;">
    <h2>✅ Thank you!</h2>
    <p>Your message has been successfully submitted. We will get back to you soon.</p>
    <a href="<?php echo new moodle_url('/'); ?>" class="btn btn-primary mt-3">Go to Home</a>
</div>

<?php
echo $OUTPUT->footer();
