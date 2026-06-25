<?php
session_start();
  $nowMonth = date('n');
  $nowDay = date('j');
  $deadlineDay = 15;
  $deadlineMonth = $nowMonth;
  if(($nowMonth == 2 && $nowDay >=26) || $nowDay >= 28) {
     $deadlineMonth++;
     if($deadlineMonth > 12) $deadlineMonth = 1;
  } elseif ($nowDay >= 13) {
     $deadlineDay = 30;
     if($nowMonth == 2) $deadlineDay = 28;
  }
  
  $isMobile = (bool)preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$_SERVER['HTTP_USER_AGENT'])||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($_SERVER['HTTP_USER_AGENT'],0,4));  
  
if(!isset($thanks)) $thanks = false;
if(!isset($appointment)) $appointment = false;
if(isset($_GET['thanks'])) $thanks = true;
$emailz = $_REQUEST['email'];
$phone = "555-555-5555";
$tel = preg_replace('/[^0-9]/s', '', $phone);

if(!empty($_POST) && empty($_REQUEST['comments'])){
      
      if($_REQUEST['gradyear'] == 'No GED or Diploma') {
        $isGOod = false;
      } else {
        $isGOod = true;
      }
      
      
      $_REQUEST['campus'] = "Peoria";
      $_REQUEST['glue_id'] = 107853;
      
      
      $_REQUEST['program'] = 'Medical Assisting';
      
      
$_REQUEST['keywords'] = urldecode(trim($_REQUEST['utm_keyword'].' '.$_REQUEST['kw'].' '.$_REQUEST['utm_campaign']));
      
         $emailz = $_REQUEST['email']; $curl_handle = curl_init();
			curl_setopt($curl_handle, CURLOPT_URL, 'http://services.graggadv.com/lead-gateway/' );
			curl_setopt($curl_handle, CURLOPT_HEADER, 1);
			curl_setopt($curl_handle, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			curl_setopt($curl_handle, CURLOPT_POST, 1);
			curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $_REQUEST);
			curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER,1);
			$response = curl_exec ($curl_handle);
							
			if ( strstr( $response, "ERROR" ) ) {
				 mail("iaprogrammer@graggadv.com", $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ." lead failure", $response . print_r($_REQUEST,true) );
			}  
      
      $thanks = true;
      if(!empty($_REQUEST['custom1'])) {
        $location = 'thanks-appointment.php?email='.$emailz.''; 
      } else {
        $location = 'thanks.php?email='.$emailz.''; 
      }
      header('Location: '.$location);
      exit;
}
?>
<!DOCTYPE html>
<html>
   <head>
      <meta content="width=device-width, initial-scale=1.0" name="viewport" />
      <title>Arizona School of Medical Assistant </title>
      <link href="css-old/styleNew.css" rel="stylesheet" />
      <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
      <link rel="icon" href="/lp/go/favicon.png" type="image/png">
      <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
      <script src="//code.jquery.com/jquery-1.12.4.js"></script>
      <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
            
      

      <script type="text/javascript">
      jQuery(document).ready(function() {
         jQuery('.carousel-control-outside').click(function(event){
           event.preventDefault();
         });
         jQuery('#slider-customers-4-next').click(function(){
           if (jQuery('#slide1').hasClass('activeSlide')) {
               jQuery('#slide1').removeClass('activeSlide');
               jQuery('#slide2').addClass('activeSlide');
           } else if (jQuery('#slide2').hasClass('activeSlide')) {
               jQuery('#slide2').removeClass('activeSlide');
               jQuery('#slide3').addClass('activeSlide');
           } else if (jQuery('#slide3').hasClass('activeSlide')) {
               jQuery('#slide3').removeClass('activeSlide');
               jQuery('#slide1').addClass('activeSlide');
           }
         });
         jQuery('#slider-customers-4-prev').click(function(){
           if (jQuery('#slide1').hasClass('activeSlide')) {
               jQuery('#slide1').removeClass('activeSlide');
               jQuery('#slide3').addClass('activeSlide');
           } else if (jQuery('#slide2').hasClass('activeSlide')) {
               jQuery('#slide2').removeClass('activeSlide');
               jQuery('#slide1').addClass('activeSlide');
           } else if (jQuery('#slide3').hasClass('activeSlide')) {
               jQuery('#slide3').removeClass('activeSlide');
               jQuery('#slide2').addClass('activeSlide');
           }
         });
         
         $('#scheduler').change(function(){
            $('#schedulerHolder').toggle();
          });
          $('#scheduler2').change(function(){
              $('#schedulerHolderX').toggle();
          });
         
        });
      </script>
      <style>
        .schedule-visit-timeholder, .schedule-visit-dateholder {
            text-align: left;
        }
        .cbHolder {
            display: inline-block;
            margin-top: 10px;
            margin-right: 10px;
        }
        .cbHolder input, .cbHolder label {
            vertical-align: middle;
        }
        .cbHolder input[type="radio"] {
            height: 10px;
            width: 10px;
            margin: 5px;
            padding:7px;
            cursor:pointer;
        }
        input[type="checkbox"], input[type="radio"] {
          margin: 0 .5em 0 1em;
          vertical-align: middle;
        }
        input[type="checkbox"]:checked, input[type="radio"]:checked {
          background-color:#333;
          border:1px solid #eee
        }
        input[type="checkbox"] {
          padding: 8px;
          width: 10px;
          border-radius: 0px;
          cursor: pointer;
          background-color: white;
          border: 1px solid #333;
        }
        #schedulerZ .field {text-align:left;}
        input.iconed.date {
    background-image: url(img/calendar.png);}
      </style>
      <!-- Google Tag Manager -->

<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-T5XQQ5H2');</script>
<!-- End Google Tag Manager -->
   </head>
   <body>
    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T5XQQ5H2"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
      <div id="header">
         <div class="container">
            <div class="wrapper">
               <div id="logo">
                  <img src="img/logo.png" alt="Drury" />
               </div>
               <div id="quickLinkTainer">
                  <a href="#programs" class="quickLink">Programs</a>
                  <a href="#why" class="quickLink">Why AZ SoMA</a>
                  <a href="#location" class="quickLink">Location</a>
                  <a href="/moodle/" class="quickLink">Get Started</a>
               </div>
               <div id="phone">
                   <a href="tel:6239007033"><strong>Call Today: </strong>(623) 900-7033</a>
               </div>
            </div>
         </div>
      </div>
      <div id="top">
         <?php if($thanks){ ?>
         <div class="wrapper">
          <div id="hero" class="thanks">
             <div class="container">
                
                <?php
                 if($appointment){
                   echo '<h1>Thank You for Requesting a Virtual Appointment!</h1><script>window.dataLayer = window.dataLayer || [];window.dataLayer.push({\'event\':\'form_submit2\',\'enhanced_conversion_data\': {"email":"'.$emailz.'",}});</script>';
                 } else {
                   echo '<h1>Thank you for your interest in AZ School of Medical Assisting.</h1><script>window.dataLayer = window.dataLayer || [];window.dataLayer.push({\'event\':\'form_submit\',\'enhanced_conversion_data\': {"email":"'.$emailz.'",}});</script>';
                 }
                 ?>
                
                <p>Congratulations on taking the first step toward a new career and a bright new future!</p>
                <p>Your request for info has been received. A friendly Admissions Representative will be in contact with you shortly. Your rep will reach out by phone and email to answer any questions you have and will help you explore your options to determine the right career path for you.</p>
             </div>
          </div>
        </div>
         <?php } else { ?> 
         <div class="wrapper">
            <div id="hero">
               <div class="container">
                  <!--  <h1><span class="subhead">Small Headline goes here</span></h1>  -->
                  <h1>Your Healthcare Career Starts Here</h1>
                  
               </div>
               <p class="innerBanner">Explore Our Clinical Medical Assistant Program</p>
               <!--  <div class="iconz">
                  <img src="img/ABHES.png">
                </div>  -->
            </div>
            <div id="topform">
               <p class="form-headerish" style="text-transform:uppercase;">DEADLINE TO APPLY – <?php echo date("F jS",mktime(0,0,0,$deadlineMonth,$deadlineDay)) ?></p>
               <h2>Take Your First Step<span class="subhead">FILL OUT THE FORM BELOW TO LEARN MORE ABOUT THE Arizona SCHOOL OF MEDICAL ASSISTANT.</span></h2>
               <form action="" method="post">
                  <div class="fieldset">
                     <div class="field">
                        <label class="sr-only">First Name</label>
                        <div class="inp_wrap person">
                           <input name="fname" placeholder="First Name" required />
                        </div>
                     </div><div class="field">
                        <label class="sr-only">Last Name</label>
                        <input name="lname" placeholder="Last Name" required />
                     </div>
                  </div>
                  <div class="field">
                     <label class="sr-only">Email</label>
                     <div class="inp_wrap email">
                        <input name="email" placeholder="Email" type="email" required />
                     </div>
                  </div>
                  <div class="field">
                     <label class="sr-only">Phone</label>
                     <div class="inp_wrap phone">
                        <input name="phone_pri" placeholder="Phone" type="tel" required />
                     </div>
                  </div>
                  <div class="field">
                     <label class="sr-only">Zip Code</label>
                     <div class="inp_wrap">
                        <input name="zip" placeholder="Zip Code" required="">
                     </div>
                  </div>
                  
                <!--  <div id="schedulerZ">
                    
                    <div class="field" style="text-align:center;"><input type="checkbox" id="scheduler" name="custom1" value="Yes-Virtual-Appointment" style="-webkit-appearance: checkbox;display: inline-block;width: 20px;height:auto;"><label>Yes! I'd like to set up a virtual appointment.</label></div>
                      <div id="schedulerHolder" style="display:none;">
                        <div class="field schedule-visit-dateholder">
                          <label><strong>What day works best for you? (Choose a highlighted day)</strong></label><br>
                          <input type="text" id="schedule-visit-date" name="custom2" class="iconed date">
                        </div>
                        <div class="field schedule-visit-timeholder">
                          <label><strong>What time of day are you available?</strong></label><br>
                          <span class="cbHolder"><input id="schedule-visit-time-9-12" name="best_time_to_call" type="radio" value="schedule-visit-time-9-12">
                          <label for="schedule-visit-time-morning">9am to 12pm</label></span>
                          <span class="cbHolder"><input id="schedule-visit-time-12-3" name="best_time_to_call" type="radio" value="schedule-visit-time-12-3">
                          <label for="schedule-visit-time-afternoon">12pm to 3pm</label></span>
                          <span class="cbHolder"><input id="schedule-visit-time-3-5" name="best_time_to_call" type="radio" value="schedule-visit-time-3-5">
                          <label for="schedule-visit-time-afternoon">3pm to 5pm</label></span>
                          <span class="cbHolder"><input id="schedule-visit-time-anytime" name="best_time_to_call" type="radio" value="schedule-visit-time-anytime">
                          <label for="schedule-visit-time-afternoon">Anytime</label></span>
                        </div>
                      </div>
                    </div>  -->
                  <!--  <div class="field">
                    <label class="sr-only">Graduation Year (HS or GED)</label>
                    <div class="sel_wrap">
                      <select name="gradyear" style="" required="">
                        <option value="">Graduation Year (HS or GED)</option>
                        <option value="No GED or Diploma">No GED or Diploma</option>
                        <?//php
                        //for($year=date('Y'); $year > 1975; $year--){
                          //echo '<option value="'.$year.'">'.$year.'</option>';
                        //}
                        ?>
                    </select>
                    </div>
                  </div>  -->
                  <div class="field">
                     <input class="submit" type="submit" value="SEND ME MORE INFO"/>
                  </div>
                  <textarea aria-hidden="true" name="comments"></textarea>
               <input name="affiliate_id" value="<?=urlencode(trim($_GET['utm_campaign'].' '.$_GET['ref']))?>" type="hidden">
<?php
foreach($_GET as $key=>$value){
	echo '<input name="'.urlencode($key).'" value="'.urlencode($value).'" type="hidden">';
}
?>
               </form>
               <p class="disclaimer">By clicking ‘Subscribe’, I agree to receive recurring informational SMS, MMS, or Email messages from AZ School Of Medical Assistant LLC. Message frequency may vary. Message & data rates may apply. Reply STOP to opt-out of further messaging. Reply HELP for more information. No mobile information will be shared nor sold with third parties/affiliates for marketing/promotional purposes, we do not share any client data with third parties. Your personal information is kept confidential and is not disclosed to any outside organizations, except as required by law or with your explicit consent, see our <a href="privacy-policy" style="color:white;">Privacy Policy</a>.</p>
            </div>
         </div>
         <?php } ?>
   </div>
      <div class="container">
        <div class="wrapper" id="programs">
          <h1 class="goHead">Train to Become a Medical Assistant in Just 20 Weeks</h1>
          <p class="goText">With the demand for skilled healthcare professionals on the rise, there’s never been a better time to start a career as a medical assistant. Through a partnership with the National Healthcareer Association (NHA), the Arizona School of Medical Assistant provides a high-quality training program that can be completed in as little as 20 weeks.</p>
          
          <h2 style="color:#333;">Program Details</h2>
          <p>This hybrid program is designed to give you the comprehensive, hands-on training you need to get started in medical assisting. Upon completing this program, students will demonstrate the flowing outcomes:</p>
          <ul  class="spacer">
            <li><b>Clinical Competence</b> – The ability to perform essential clinical procedures including diagnostic testing, taking vital signs, administering injections, and assisting with minor surgical procedures.</li>
            <li><b>Administrative Competence</b> – A mastery of medical office administrative tasks, including operating electronic health records (EHR) systems, scheduling appointments, managing medical records, and handling billing and coding.</li>
            <li><b>Communication Skills</b> – Effective communication with patients, healthcare professionals, and colleagues, with a demonstrated ability to maintain patient confidentiality and professionalism in all interactions.</li>
            <li><b>Patient Care and Interaction</b> – An ability to educate procedures, medications, and follow-up care, while demonstrating empathy and sensitivity.</li>
            <li><b>Medical Ethics and Legal Compliance</b> – An understanding of and adherence to medical ethics and legal standards in healthcare practice, with a knowledge of patient rights and responsibilities.</li>
            <li><b>Critical Thinking and Problem-Solving</b> – An ability to think critically, adapt quickly to changing situations and quickly make decisions to solve problems in various medical scenarios.</li>
            <li><b>Professionalism and Teamwork</b> – Effective collaboration and teamwork skills, along with demonstrated professionalism in the workplace.</li>
            <li><b>Continuing Education and Professional Development</b> – An understanding of the importance of lifelong learning and a commitment to staying updated on medical advancements through personal development and continuing education.</li>
            <li><b>Cultural Competence</b> – An ability to provide culturally sensitive and competent care, with an understanding and appreciation of cultural diversity in patient populations.</li>
            <li><b>Certification Examination Preparation</b> – Upon completing this program, you will be equipped with the skills and knowledge you need to pass the Certified Clinical Medical Assistant (CCMA) exam.</li>
          </ol>
          
          <video style="width: 90%;height: 400px;text-align: center;margin: 0 auto;display: block;" controls>
            <source src="/img/MedicalSchool.mp4" type="video/mp4">
          Your browser does not support the video tag.
          </video>
              <p>&nbsp;</p>
              <p>AZ School of Medical Assistant students may use Affirm financing as an option to invest in their education. Affirm supports students with financing one payment at a time.* With Affirm, you’ll never owe more than you agree to up front. Instead, you’ll always get a flexible, transparent, and convenient way to pay overtime.</p>

              <p>Make sure to ask your Admissions Rep for details on connecting to this financial option!</p>
              
              <p style="font-size:80%;font-style:italic;">*Source: <a href="https://www.affirm.com/how-it-works" target="_blank">https://www.affirm.com/how-it-works</a><br/>
              **Terms and Conditions apply. Subject to Approval.</p>
              <p>&nbsp;</p>
              
        </div>
      </div>
      <div class="grayBG">
        <div class="container">
          <div class="wrapper" id="why">
                 <h1 class="goHead">Why Arizona School of Medical Assistant?</h1>
                 <div class="split scooty">
                  <ul class="spacer">
                    <li><b>An Experienced and Qualified Faculty</b> – Our instructors are professionals with extensive experience in the healthcare industry, ensuring a high standard of education.</li>
                    <li><b>State-of-the-Art Facilities</b> – We use industry-current equipment in our modern classrooms, labs, and simulation spaces to facilitate effective learning and prepare you for working in the real world.</li>
                    <li><b>Industry-Relevant Curriculum</b> – Through a partnership with NHA, the Arizona School of Medical Assistant regularly updates curriculum to align with the latest healthcare standards, ensuring graduates are well-prepared for evolving industry needs.</li>
                    <li><b>Strong Community Connections</b> – We partner with local healthcare facilities to provide students with real-world experience and job placement opportunities.</li>
                    <li><b>Hybrid Learning</b> – Our blended learning approach allows students the flexibility to study and do the lecture and written portion of the curriculum at home when it is convenient for them.</li>
                    </ul>
                 </div>
                 <div class="split" style="text-align:center;"><img src="img/box1.jpg"></div>
              </div>
        </div>
      </div>
      
      <div class="">
        <div class="container">
            <div class="cBlocks" id="location">
                <div class="centerizer">
                  <h1 class="goHead" style="margin-bottom: 1rem">Location</h1>
                  
                  <div class="duoSplit">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d53165.61249495341!2d-112.29004743734308!3d33.60917596500456!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x872b426f0546768b%3A0xa7f40ff583b572d5!2s13943%20N%2091st%20Ave%20A%20101%2C%20Peoria%2C%20AZ%2085381!5e0!3m2!1sen!2sus!4v1710957452384!5m2!1sen!2sus" style="border:0;width:400px;min-height:300px;" allowfullscreen="" loading="lazy"></iframe>
                    <p><b>Peoria</b></p>
                  </div>
                  
                  
                  
                </div>
                
                <p><b>AZ School of Medical Assistant students may use Affirm financing as an option to invest in their education. Affirm supports students with financing one payment at a time.* With Affirm, you’ll never owe more than you agree to up front. Instead, you’ll always get a flexible, transparent, and convenient way to pay over time.</b></p>
                
                <p><b>Make sure to ask your Admissions Rep for details on connecting to this financial option!</b></p>
                
                 
                
                <p style="font-size:70%;font-style:italic;">*Source: <a href="https://www.affirm.com/how-it-works" target="_blank">https://www.affirm.com/how-it-works</a><br/>
                
                **Terms and Conditions apply. Subject to Approval.</p>
                <p>&nbsp;</p>
            </div>
          </div>
        </div>
      
      
      <?php if(!$thanks) { ?>
      <div id="bottomform">
         <div class="container">
            <p class="form-headerish" style="text-transform:uppercase;">DEADLINE TO APPLY – <?php echo date("F jS",mktime(0,0,0,$deadlineMonth,$deadlineDay)) ?></p>
            <h2>Take Your First Step <span class="subhead">FILL OUT THE FORM BELOW TO LEARN MORE ABOUT THE Arizona SCHOOL OF MEDICAL ASSISTANT.</span></h2>
            <form action="" method="post">
               <div class="fieldset">
                  <div class="field">
                     <label class="sr-only">First Name</label>
                     <div class="inp_wrap person">
                        <input name="fname" placeholder="First Name" required />
                     </div>
                  </div><div class="field">
                     <label class="sr-only">Last Name</label>
                     <input name="lname" placeholder="Last Name" required />
                  </div>
               </div>
               <div class="field">
                  <label class="sr-only">Email</label>
                  <div class="inp_wrap email">
                     <input name="email" placeholder="Email" type="email" required />
                  </div>
               </div>
               <div class="field">
                  <label class="sr-only">Phone</label>
                  <div class="inp_wrap phone">
                     <input name="phone_pri" placeholder="Phone" type="tel" required />
                  </div>
               </div>
               <div class="field">
                     <label class="sr-only">Zip Code</label>
                     <div class="inp_wrap">
                        <input name="zip" placeholder="Zip Code" required="">
                     </div>
                  </div>
              <!-- <div id="schedulerX">
                    
                    <div class="field" style="text-align:center;"><input type="checkbox" id="scheduler2" name="custom1" value="Yes-Virtual-Appointment" style="-webkit-appearance: checkbox;display: inline-block;width: 20px;height:auto;"><label>Yes! I'd like to set up a virtual appointment.</label></div>
                      <div id="schedulerHolderX" style="display:none;">
                        <div class="field schedule-visit-dateholder">
                          <label><strong>What day works best for you? (Choose a highlighted day)</strong></label><br>
                          <input type="text" id="SVDTwo" name="custom2" class="iconed date">
                        </div>
                        <div class="field schedule-visit-timeholder">
                          <label><strong>What time of day are you available?</strong></label><br>
                          <span class="cbHolder"><input id="schedule-visit-time-9-12" name="best_time_to_call" type="radio" value="schedule-visit-time-9-12">
                          <label for="schedule-visit-time-morning">9am to 12pm</label></span>
                          <span class="cbHolder"><input id="schedule-visit-time-12-3" name="best_time_to_call" type="radio" value="schedule-visit-time-12-3">
                          <label for="schedule-visit-time-afternoon">12pm to 3pm</label></span>
                          <span class="cbHolder"><input id="schedule-visit-time-3-5" name="best_time_to_call" type="radio" value="schedule-visit-time-3-5">
                          <label for="schedule-visit-time-afternoon">3pm to 5pm</label></span>
                          <span class="cbHolder"><input id="schedule-visit-time-anytime" name="best_time_to_call" type="radio" value="schedule-visit-time-anytime">
                          <label for="schedule-visit-time-afternoon">Anytime</label></span>
                        </div>
                      </div>
                    </div>  -->
               <!--  <div class="field">
                    <label class="sr-only">Graduation Year (HS or GED)</label>
                    <div class="sel_wrap">
                      <select name="gradyear" style="" required="">
                        <option value="">Graduation Year (HS or GED)</option>
                        <option value="No GED or Diploma">No GED or Diploma</option>
                        <?//php
                        //for($year=date('Y'); $year > 1975; $year--){
                          //echo '<option value="'.$year.'">'.$year.'</option>';
                        //}
                        ?>
                    </select>
                    </div>
                  </div>  -->
               <div class="field">
                  <input class="submit" type="submit" value="SEND ME MORE INFO"/>
               </div>
               <textarea aria-hidden="true" name="comments"></textarea>
               <input name="affiliate_id" value="<?=urlencode(trim($_GET['utm_campaign'].' '.$_GET['ref']))?>" type="hidden">
<?php
foreach($_GET as $key=>$value){
	echo '<input name="'.urlencode($key).'" value="'.urlencode($value).'" type="hidden">';
}
?>
            </form>
            <p class="disclaimer">By clicking ‘Subscribe’, I agree to receive recurring informational SMS, MMS, or Email messages from AZ School Of Medical Assistant LLC. Message frequency may vary. Message & data rates may apply. Reply STOP to opt-out of further messaging. Reply HELP for more information. No mobile information will be shared nor sold with third parties/affiliates for marketing/promotional purposes, we do not share any client data with third parties. Your personal information is kept confidential and is not disclosed to any outside organizations, except as required by law or with your explicit consent, see our <a href="privacy-policy" style="color:white;">Privacy Policy</a>.</p>
         </div>
      </div>
      <?php } else if ($isGOod) { ?>
      <!--  <iframe src="https://app.squarespacescheduling.com/schedule.php?owner=23254087" label="Schedule Appointment" width="100%" height="800" frameBorder="0"></iframe><script src="https://embed.acuityscheduling.com/js/embed.js" type="text/javascript"></script>  -->
      <?php } ?>
      <div id="footer">
         <div class="container">
            <!-- <p class="legal"></p>
            <p>&nbsp;</p>  -->
            <p class="copyright" style="text-align:center;"><?=date('Y')?> &copy; Arizona School of Medical Assistant All Rights Reserved</p>
            
         </div>
      </div>
      <?php /*
      <script type="text/javascript">
        window.lhnJsSdkInit = function () {
          lhnJsSdk.setup = {
            application_id: "25CB2181-BE71-4C5A-81D9-BDC274E3BEFD",
            application_secret: "8LOoRoUeA+9AAjgb5x/hxK0kjSJXMqBe58oDpztPn6fkdZLEoM"
          };
          lhnJsSdk.controls = [{
            type: "hoc",
            id: "819CB161-1526-40BC-8E36-B4986BD06B88"
          }];
        };
        (function (d, s) {
          var newjs, lhnjs = d.getElementsByTagName(s)[0];
          newjs = d.createElement(s);
          newjs.src = "https://developer.livehelpnow.net/js/sdk/lhn-jssdk-current.min.js";
          lhnjs.parentNode.insertBefore(newjs, lhnjs);
        }(document, "script"));
      </script>
      */ ?>
      
      <script async type="text/javascript">
        jQuery(document).ready(function() {
          
            var d = new Date();
            var day = d.getDay();
            var maxi = '';
            var mini = '';
            if(day==0){maxi = 11; mini = 8;}
            else if(day>=1 && day<=3){maxi = 10; mini = 7;}
            else if(day==4 ){maxi = 12; mini = 7;}
            else if(day==5 ){maxi = 12; mini = 7;}
            else {maxi = 11; mini = 8;}
          
          jQuery("#schedule-visit-date").datepicker({
            dateFormat : 'mm-dd-yy',
            minDate: '+'+mini+'D',
            maxDate: '+'+maxi+'D',
            beforeShowDay: $.datepicker.noWeekends
          });
          jQuery("#SVDTwo").datepicker({
            dateFormat : 'mm-dd-yy',
            minDate: '+'+mini+'D',
            maxDate: '+'+maxi+'D',
            beforeShowDay: $.datepicker.noWeekends
          });
        });
      </script>
  </body>
</html>
