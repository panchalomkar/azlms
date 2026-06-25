<?php
require_once('config.php');

require_once($CFG->libdir . '/moodlelib.php'); // Required for email_to_user()
$thank_you_message = '';
$is_for_submit = 0;
if(!empty($_POST)){

    $user_input = required_param('captcha_input', PARAM_TEXT); //trim($_POST['captcha_input'] ?? '');
    $user_input = trim($user_input);
    if (isset($SESSION->captcha_code) && strtolower($user_input) === strtolower($SESSION->captcha_code)) {
       // echo "✅ CAPTCHA correct!";

        // Get the recipient user object
        $recipient = "support@azschoolofmedicalassistant.com";
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

               $thank_you_message =  "Thank you for subscribing!";
                //exit;
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
                                <p>Thank you for reaching out to the Arizona School of Medical Assistant. We have received your inquiry, and a member of our team will be contacting you shortly to provide more information and answer your questions.</p>
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
                    //echo "Thank you for showing interest! Our team will connect with you shortly to assist further.";
                     $thank_you_message =  "Thank you for showing interest! Our team will connect with you shortly to assist further.";
                }else{
                    //echo "Thank you for reaching out! Our team will get in touch with you shortly. We appreciate your interest.";
                     $thank_you_message =  "Thank you for reaching out! Our team will get in touch with you shortly. We appreciate your interest.";
                }

                $fullname = required_param('yourName', PARAM_TEXT);
                $phone    = optional_param('yourPhone', '', PARAM_TEXT);
                $email    = required_param('yourEmail', PARAM_EMAIL);
                $subject  = required_param('yourMessage', PARAM_TEXT);

                $sent = send_contact_to_hubspot_oauth( $fullname,$email,$phone,$subject);
               // exit;
            }
            $is_for_submit = 1;
    }else{
         $thank_you_message =  "Invalid  Captcha.";
    }
    $SESSION->captcha_code = '';
           
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


function send_contact_to_hubspot_oauth($name,$email, $phone,$message) {
    $access_token = 'pat-na1-7defdeb7-d6a7-4c36-a5e2-296808bbe172';// 'pat-na1-ee459c74-9b67-4d0f-955f-f237dacbc301'; // Access token NOT an API key  
    $url_owner = 'https://api.hubapi.com/crm/v3/owners';
    $ch_owner = curl_init();
    curl_setopt($ch_owner, CURLOPT_URL, $url_owner);
    curl_setopt($ch_owner, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $access_token,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch_owner, CURLOPT_RETURNTRANSFER, true);
    $response_owner = curl_exec($ch_owner);
    $http_code = curl_getinfo($ch_owner, CURLINFO_HTTP_CODE);
    curl_close($ch_owner);   
    $owner_id = '';

    if ($http_code === 200) {
        $owners = json_decode($response_owner, true);
        $owner_id = $owners['results'][0]['id'];
    } else {
        // echo "Error fetching owners. HTTP Status Code: $http_code\n";
        // echo "Response: $response";
    }   

    $url = 'https://api.hubapi.com/crm/v3/objects/contacts';
    // $name = "Mohan Akos";
    // $email = "mohan.pal11@akosmdtech.com";
    // $phone = "9711500513";
    // $message = "Hi, This is for test";

    $name = $name;
    $email = $email;
    $phone =  $phone;
    $message = $message;

    $data = [
        'properties' => [
            'email' => $email,
            'firstname' =>$name,
            'phone' => $phone,
            'message' =>$message,
            'hubspot_owner_id'=>$owner_id,
            'hs_lead_status' => 'NEW'
        ]
    ];

    $payload = json_encode($data);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        "Authorization: Bearer $access_token"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    //  echo "<pre>";
    //  print_r( $response);

    if ($status === 201) {
        return true;
    } else {
        error_log("HubSpot sync failed: $response");
        return false;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
    <?php 
    $page_title = "Thank You for Contact";
    require_once('header.php');
    ?>
<?php if($is_for_submit == 1){ ?>
       <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-16575983369"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'AW-16575983369');
</script>
<?php } ?>
    <style>    

    .thank-you-box {
      text-align: center;
      background: #fff;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .thank-you-box h1 {
      color: #8ca1dfff;
      margin-bottom: 10px;
    }

    .thank-you-box p {
      font-size: 16px;
      color: #333;
    }

    .thank-you-box a {
      margin-top: 20px;
      display: inline-block;
      text-decoration: none;
      color: #fff;
      background: #8a8cdbff;
      padding: 10px 20px;
      border-radius: 5px;
    }

    .thank-you-box a:hover {
      background: #a1a7dfff;
    }

    </style>
    </head> 
    <?php
    require_once('config.php');
    ?>
    <body>
           <!-- Event snippet for Form Fill - Virtual Appointment conversion page --> 
<?php if($is_for_submit == 1){ ?>
 <script> gtag('event', 'conversion', {'send_to': 'AW-16575983369/KNCvCJyBqJQbEIneheA9'}); </script>
<?php } ?>
        <nav class="customNavbar">
        
                <?php
                require_once('nav.php');
                ?>
        
        </nav>
    <?php if($is_for_submit == 1){ ?>
        <section class="heroSection aboutHeroSection">
            <div class="container position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="heroContent">
                <h1> Thank you </h1>
                </div>
                <div>
                <svg width="153" height="153" viewBox="0 0 153 153" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M26.7981 76.5C26.791 68.8219 27.631 61.1701 29.3137 53.6976C29.9284 50.9687 31.9481 48.9714 34.683 48.3863L45.1928 46.1391C47.4358 45.6589 49.1574 44.2738 50.1058 42.1856L61.9391 16.1397C63.7925 12.0607 61.444 7.36522 57.0685 6.4012L40.8204 2.82124C33.4049 1.18755 26.1079 4.28401 22.1316 10.7527C9.89284 30.6631 3.79346 53.4956 3.91688 76.5C3.79346 99.5044 9.89254 122.337 22.1313 142.247C26.1076 148.716 33.4046 151.812 40.8201 150.179C46.236 148.986 51.6526 147.792 57.0682 146.599C61.444 145.635 63.7922 140.939 61.9388 136.86L50.1055 110.814C49.1571 108.726 47.4355 107.341 45.1925 106.861L34.6827 104.614C31.9478 104.029 29.9281 102.031 29.3134 99.3024C27.631 91.8299 26.791 84.1781 26.7981 76.5ZM58.2633 35.7892H136.749C138.854 35.7892 140.837 36.3211 142.574 37.2567L89.9964 72.2375C89.3417 72.6732 88.6164 72.8911 87.8962 72.8911C87.1761 72.8911 86.4511 72.6732 85.7961 72.2375L50.6521 48.8554C52.278 47.6834 53.583 46.0904 54.4586 44.1632L58.2633 35.7892ZM146.392 40.4467L102.114 69.9058L146.891 111.884C148.322 109.827 149.088 107.38 149.086 104.874V48.1257C149.086 45.2267 148.076 42.5564 146.392 40.4467ZM143.467 115.212C141.47 116.518 139.135 117.213 136.749 117.211H58.2633L54.4586 108.837C52.8861 105.376 49.9292 102.992 46.2148 102.191L77.757 72.6194L83.1622 76.2158C84.6217 77.187 86.2563 77.6723 87.8956 77.6723C89.535 77.6723 91.1696 77.1867 92.6291 76.2158L98.0346 72.6194L143.467 115.212ZM45.2777 51.01L73.6786 69.9055L40.5365 100.976L35.6826 99.9383C34.7721 99.7435 34.1825 99.1604 33.9778 98.252C32.371 91.1181 31.5791 83.8117 31.5791 76.5C31.5791 69.1883 32.3713 61.8819 33.9778 54.748C34.1825 53.8396 34.7718 53.2566 35.6826 53.0617L45.2777 51.01Z"
                    fill="white" />
                </svg>
                </div>
            </div>
            </div>
            <div class="bg-shape aboutHeroBg1"></div>
        </section>
        <section class="contactUsSectionC">
            <div class="container">
              <div class="thank-you-box">
                <h1>Thank You!</h1>
                <p><?php echo $thank_you_message;?></p>
                <a href="/">Back to Home</a>
            </div>
            
            </div>
            </div>
        </section> 
        <?php } ?> 

        <?php if($is_for_submit == 0){ ?>
        <section class="heroSection aboutHeroSection">
            <div class="container position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="heroContent">
                <h1> Thank you </h1>
                </div>
                <div>
                <svg width="153" height="153" viewBox="0 0 153 153" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M26.7981 76.5C26.791 68.8219 27.631 61.1701 29.3137 53.6976C29.9284 50.9687 31.9481 48.9714 34.683 48.3863L45.1928 46.1391C47.4358 45.6589 49.1574 44.2738 50.1058 42.1856L61.9391 16.1397C63.7925 12.0607 61.444 7.36522 57.0685 6.4012L40.8204 2.82124C33.4049 1.18755 26.1079 4.28401 22.1316 10.7527C9.89284 30.6631 3.79346 53.4956 3.91688 76.5C3.79346 99.5044 9.89254 122.337 22.1313 142.247C26.1076 148.716 33.4046 151.812 40.8201 150.179C46.236 148.986 51.6526 147.792 57.0682 146.599C61.444 145.635 63.7922 140.939 61.9388 136.86L50.1055 110.814C49.1571 108.726 47.4355 107.341 45.1925 106.861L34.6827 104.614C31.9478 104.029 29.9281 102.031 29.3134 99.3024C27.631 91.8299 26.791 84.1781 26.7981 76.5ZM58.2633 35.7892H136.749C138.854 35.7892 140.837 36.3211 142.574 37.2567L89.9964 72.2375C89.3417 72.6732 88.6164 72.8911 87.8962 72.8911C87.1761 72.8911 86.4511 72.6732 85.7961 72.2375L50.6521 48.8554C52.278 47.6834 53.583 46.0904 54.4586 44.1632L58.2633 35.7892ZM146.392 40.4467L102.114 69.9058L146.891 111.884C148.322 109.827 149.088 107.38 149.086 104.874V48.1257C149.086 45.2267 148.076 42.5564 146.392 40.4467ZM143.467 115.212C141.47 116.518 139.135 117.213 136.749 117.211H58.2633L54.4586 108.837C52.8861 105.376 49.9292 102.992 46.2148 102.191L77.757 72.6194L83.1622 76.2158C84.6217 77.187 86.2563 77.6723 87.8956 77.6723C89.535 77.6723 91.1696 77.1867 92.6291 76.2158L98.0346 72.6194L143.467 115.212ZM45.2777 51.01L73.6786 69.9055L40.5365 100.976L35.6826 99.9383C34.7721 99.7435 34.1825 99.1604 33.9778 98.252C32.371 91.1181 31.5791 83.8117 31.5791 76.5C31.5791 69.1883 32.3713 61.8819 33.9778 54.748C34.1825 53.8396 34.7718 53.2566 35.6826 53.0617L45.2777 51.01Z"
                    fill="white" />
                </svg>
                </div>
            </div>
            </div>
            <div class="bg-shape aboutHeroBg1"></div>
        </section>
        <section class="contactUsSectionC">
            <div class="container">
              <div class="thank-you-box">
                <h1>Invalid Captcha!</h1>
                <p><?php echo $thank_you_message;?></p>
                <a href="/">Back to Home</a>
            </div>
            
            </div>
            </div>
        </section> 
        <?php } ?> 

        <?php
            require_once('footer.php');
        ?>


        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="assets/js/main.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
        crossorigin="anonymous"></script>    
    </body>
</html>



