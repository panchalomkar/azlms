<?php
require_once('config.php');

require_once($CFG->libdir . '/moodlelib.php'); // Required for email_to_user()

// Get the recipient user object
$recipient = "support@azschoolofmedicalassistant.com";
// Get the sender (usually noreply user or an admin)

// Email details



// Optional: Add context for logging
$context = context_system::instance();

// Get POST data.
$submit_type = required_param('submit_type', PARAM_TEXT);
// if($submit_type  == 'request_info'){
// }else{
// }
if($submit_type == 'email_subscription'){
    $email    = required_param('yourEmail', PARAM_EMAIL);   
    $record = new stdClass();
    $record->email_id    = $email;   
    $record->timecreated = date("Y-m-d");

    $record->status = 1;
    $DB->insert_record('email_subscription', $record);
    // send email to enqury user
 
    $sender=  $email;

    $subject = "You're subscribed to our Moodle Newsletter!";
    $messagetext = "Hi,
        Thank you for subscribing to our newsletter. 
        You'll start receiving updates and news directly in your inbox. If you did not subscribe, you can safely ignore this message.
        - Moodle Team";;
    $messagehtml = "<p>Hi,</p>
<p>Thank you for subscribing to our newsletter. You'll start receiving updates and news directly in your inbox.</p>
<p>If you did not subscribe, you can safely ignore this message.</p>
<p>— <em>The Moodle Team</em></p>";
    $emailresult = email_to_user($recipient, $sender, $subject, $messagetext, $messagehtml);

    echo "Thank you for subscribing!";
    exit;
}else{
    $fullname = required_param('yourName', PARAM_TEXT);
    $phone    = optional_param('yourPhone', '', PARAM_TEXT);
    $email    = required_param('yourEmail', PARAM_EMAIL);
    $subject  = required_param('yourMessage', PARAM_TEXT);
    $record = new stdClass();
    $record->fullname    = $fullname;
    $record->phone       = $phone;
    $record->email       = $email;
    $record->subject     = $subject;
    $record->timecreated = time();
    $record->submit_from = $submit_type;
    // Insert into your custom table. Table must exist: mdl_contact_form
    $DB->insert_record('contact_form', $record);
    // sending email to the admin
    //$recipient =  $email;

    $recipient = new stdClass();
    $recipient->id = -99; // fake ID
    $recipient->email = $email; // your target email
    $recipient->firstname = $fullname;
    $recipient->lastname = '';
    $recipient->maildisplay = 1;
    $recipient->emailstop = 0;
    $recipient->confirmed = 1;
    $recipient->auth = 'manual';
    $recipient->deleted = 0;
    $recipient->suspended = 0;

    $sender ="support@azschoolofmedicalassistant.com";
    $subjectEmail = "Your Information Request Has Been Received";
    $messagetext =  "Dear  $fullname,
                    Thank you for reaching out to the Arizona School of Medical Assistant. We have received your inquiry, 
                    and a member of our team will be contacting you shortly to provide more information and answer your 
                    questions.

                    We look forward to assisting you and supporting your educational journey.


                    Best regards,  
                    Admissions Team,
                    Arizona School of Medical Assistant";

    $messagehtml = "<p>Dear <strong>[First Name]</strong>,</p>
                    <p>Thank you for reaching out to the Arizona School of Medical Assistant. We have received your inquiry, 
                    and a member of our team will be contacting you shortly to provide more information and answer your 
                    questions.</p>
                    <p>We look forward to assisting you and supporting your educational journey.</p>                    

                    <p>Best regards,<br>
                    Admissions Team.
                     Arizona School of Medical Assistant
                     </p>";
    $emailresult = email_to_user($recipient, $sender, $subjectEmail, $messagetext, $messagehtml);

    // send email to support team
    //$recipient = "support@azschoolofmedicalassistant.com";
        $recipient = new stdClass();
        $recipient->id = -999; // fake ID
        $recipient->email = "support@azschoolofmedicalassistant.com"; // your target email
        $recipient->firstname = "Support";
        $recipient->lastname = 'Team';
        $recipient->maildisplay = 1;
        $recipient->emailstop = 0;
        $recipient->confirmed = 1;
        $recipient->auth = 'manual';
        $recipient->deleted = 0;
        $recipient->suspended = 0;

    $sender = $email;
    $subjectEmail = "New Information Enquiry Submitted by ".$fullname;

    $messagetext = "Hello Support Team,

                    A new information enquiry has been submitted via the site.<br/><br/>

                    Details:
                    --------------------------------------
                    Name: $fullname
                    Email: $email    
                    Phone Number: $phone   
                    Submitted on: ".date('M-d-Y').";

                    Message: 
                    $subject
                    --------------------------------------

                    Please follow up with the user as soon as possible.

                    – Moodle System Notification";

    $messagehtml  = "<p>Hello Support Team,</p>
                <p>A new information enquiry has been submitted via the site.</p>

                <hr>
                <p><strong>Name:</strong> $fullname<br>
                <strong>Email:</strong> <a href='$email'>$email</a><br>   
                <strong>Phone Number:</strong>$phone<br>             
                <p><strong>Message:</strong><br>
                $subject</p>
                <hr>

                <p>Please follow up with the user as soon as possible.</p>

                <p>– <em>Moodle System Notification</em></p>";
        $emailresult = email_to_user($recipient, $sender, $subjectEmail, $messagetext, $messagehtml);
       // echo $emailresult." res";

    if($submit_type  == 'request_info'){
        echo "Thank you for showing interest! Our team will connect with you shortly to assist further.";
    }else{
        echo "Thank you for reaching out! Our team will get in touch with you shortly. We appreciate your interest.";
    }
    exit;
}

function sendEmail(){





// Send the email
$emailresult = email_to_user($recipient, $sender, $subject, $messagetext, $messagehtml);

// Check if it worked
if ($emailresult) {
    echo "Email sent successfully!";
} else {
    echo "Failed to send email.";
}
}


//redirect(new moodle_url('/thankyou.php'), 'Your message has been saved!', 2);


