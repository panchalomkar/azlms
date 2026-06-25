<?php
// Send email to users 4 weeks after registration
define('CLI_SCRIPT', true); // Mark as CLI script
define('EMAIL_DEBUG', true); // Set to false to disable CLI output
require_once('config.php');
require_once($CFG->libdir . '/moodlelib.php'); // Required for email_to_user()
require_once($CFG->dirroot . '/user/lib.php');

global $DB;



// Get time 4 weeks ago
echo $time4weeksago = time() - (4 * 7 * 24 * 60 * 60);
$start = $time4weeksago - (12 * 60 * 60); // 12 hours before
$end = $time4weeksago + (12 * 60 * 60);   // 12 hours after

// Query users
$users = $DB->get_records_select(
    'user',
    'timecreated BETWEEN ? AND ? AND deleted = 0 AND confirmed = 1 AND suspended = 0',
    [$start, $end]
);

if (EMAIL_DEBUG) {
    echo "Found " . count($users) . " users to email.\n";
}

foreach ($users as $user) {
    // Skip admin or guest
    if (is_siteadmin($user) || $user->id == 1) {
        continue;
    }
    // Prepare message
    $subject = "You've been with us 4 weeks!";
    $message = "Hello {$user->firstname},\n\n Thank you for being with us for the past 4 weeks! We hope you're enjoying your learning journey so far.\n\n Please give feedback by clicking the link.\n\n

    \n\nBest regards,\n
                            Admissions Team.\n
                            Arizona School of Medical Assistant";

    // Send email
    if($user->email !=''){
        $recipient = new stdClass();
        $recipient->id = -999; // fake ID
        $recipient->email = $user->email; // your target email
        $recipient->firstname = "Support";
        $recipient->lastname = 'Team';
        $recipient->maildisplay = 1;
        $recipient->emailstop = 0;
        $recipient->confirmed = 1;
        $recipient->auth = 'manual';
        $recipient->deleted = 0;
        $recipient->suspended = 0;

        $sender = 'support@azschoolofmedicalassistant.com';// core_user::get_support_user();
       // $result = email_to_user($recipient, $supportuser, $subject, $message);

        $messagehtml = "<p>Dear <strong>{$user->firstname}</strong>,</p>
                            <p>Thank you for being with us for the past 4 weeks! We hope you're enjoying your learning journey so far.</p>
                            <p>Please give feedback by clicking the link.</p>                    
                            <p><a href='https://docs.google.com/forms/d/e/1FAIpQLSfd2u2hJq-9U25O5l19XNWRiGJOiNUsJ3X8SBIIQlsstguopg/viewform?usp=header'>https://docs.google.com/forms/d/e/1FAIpQLSfd2u2hJq-9U25O5l19XNWRiGJOiNUsJ3X8SBIIQlsstguopg/viewform?usp=header</a></p>       
                            <p>Best regards,<br>
                            Admissions Team.
                            Arizona School of Medical Assistant
                            </p>";

        $result = email_to_user($recipient, $sender, $subject, $messagehtml, $message);
              
        if (EMAIL_DEBUG) {
            echo "Email to {$user->email}: " . ($result ? "Sent" : "Failed") . "\n";
        }

    }   

}