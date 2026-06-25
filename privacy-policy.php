<!DOCTYPE html>
<html lang="en">

<head>
 <?php 
  $page_title = "Contact";
  require_once('header.php');
 ?>

   <style>
/* Modal background */
.modal {
  display: none;
  position: fixed;
  z-index: 9999;
  left: 0; top: 0;
  width: 100%; height: 100%;
  background: rgba(0, 0, 0, 0.6);
}

.modal-content {
  background: #fff;
  width: 50%;
  margin: 10% auto;
  padding: 20px;
  position: relative;
  border-radius: 5px;
}

.close-modal {
  position: absolute;
  top: 10px;
  right: 15px;
  font-size: 28px;
  cursor: pointer;
}

/* privacy policy start */
.privacyPolicyHeroContent p {
  margin-bottom: 0;
  font-size: 1.5rem;
}

.privacyPolicySecs {
  border-bottom: 1px solid #E5E5E5;
  padding: 1rem 0;
}

.privacyPolicySecs:last-child {
  border-bottom: none;
}

.privacyPolicySecs h2,
.privacyPolicySecs p {
  margin-bottom: 0.5rem;
}

.PrivacyPolicySubSecs h3 {
  margin-bottom: 0.5rem;
  font-size: 1.5rem;
}

.privacyPolicySecs address {
  font-size: 1.3rem;
}

.privacyPolicySecs ul {
  margin-left: 2rem !important;
  margin-bottom: 1rem !important;
}

.privacyPolicySecs ul,
.privacyPolicySecs ul li {
  list-style: disc !important;
}

/* privacy policy end */
</style>
</head>
<?php
require_once('config.php');
?>
<body>
  <nav class="customNavbar">
 
        <?php
        require_once('nav.php');
        ?>
 
  </nav>

  <section class="privacyPolicySection my-4">
        <div class="container">
            <div class="PrivacyPolicySecsMain">
                <div class="privacyPolicySecs">
                    <h2>1. Overview</h2>
                    <p>Welcome to the website of the <strong>Arizona School of Medical Assistant (AZMA)</strong>.
                        Your privacy is important to us. This Privacy Policy explains how we collect, use, and protect
                        the personal information you share with us through our website
                        <a href="https://www.azschoolofmedicalassistant.com">www.azschoolofmedicalassistant.com</a>.
                    <p>By using our website, you agree to the terms described in this Privacy Policy.</p>
                </div>
                <div class="privacyPolicySecs">
                    <h2>2. Information We Collect</h2>
                    <div class="PrivacyPolicySubSecs">
                        <h3>A. Information You Provide</h3>
                        <p>We collect personal information when you:</p>
                        <ul>
                            <li>Complete an online form or request information</li>
                            <li>Apply for admission or financial aid</li>
                            <li>Subscribe to a newsletter</li>
                            <li>Communicate with us via email or chat</li>
                        </ul>
                        <p>The information you may provide includes:</p>
                        <ul>
                            <li>Name</li>
                            <li>Email address</li>
                            <li>Phone number</li>
                            <li>Mailing address</li>
                            <li>Program of interest or other academic details</li>
                            <li>Any other information you choose to share</li>
                        </ul>
                    </div>
                    <div class="PrivacyPolicySubSecs">
                        <h3>B. Information Collected Automatically</h3>
                        <p>When you visit our website, certain information is automatically collected, including:</p>
                        <ul>
                            <li>Your IP address and general location</li>
                            <li>Browser type, operating system, and device type</li>
                            <li>Pages visited, time spent, and referring website</li>
                            <li>Cookies and similar tracking technologies</li>
                        </ul>
                        <p>This information helps us improve website performance, security, and user experience.</p>
                    </div>
                </div>
                <div class="privacyPolicySecs">
                    <h2>3. Use of Cookies and Tracking Technologies</h2>
                    <p>Our website uses cookies and similar technologies to:</p>
                    <ul>
                        <li>Enhance site functionality</li>
                        <li>Analyze site traffic and user behavior</li>
                        <li>Improve marketing and outreach</li>
                    </ul>
                    <p>You can manage cookie settings through your browser preferences. Disabling cookies may limit
                        certain features of the site.</p>
                    <p>We may also use <strong>Google Analytics</strong> or similar tools to collect non-personal data
                        for statistical purposes. These tools do not identify you individually.</p>
                </div>
                <div class="privacyPolicySecs">
                    <h2>4. How We Use Your Information</h2>
                    <p>AZMA may use your information to:</p>
                    <ul>
                        <li>Respond to your inquiries or requests</li>
                        <li>Provide information about programs, admissions, or events</li>
                        <li>Process applications and enrollment</li>
                        <li>Improve website functionality and services</li>
                        <li>Communicate important updates or policy changes</li>
                        <li>Comply with legal or accreditation requirements</li>
                    </ul>
                </div>
                <div class="privacyPolicySecs">
                    <h2>5. How We Share Information</h2>
                    <p>We do <strong>not sell</strong> or rent your personal data.</p>
                    <p>We may share limited information only with:</p>
                    <ul>
                        <li>Service providers that support website or communication functions (e.g., hosting, email
                            marketing)</li>
                        <li>Accrediting, licensing, or governmental agencies when required by law</li>
                        <li>Authorized school personnel for legitimate educational purposes</li>
                    </ul>
                    <p>All third parties are required to safeguard your information and use it only for authorized
                        purposes.</p>
                </div>
                <div class="privacyPolicySecs">
                    <h2>6. Student Records (FERPA Compliance)</h2>
                    <p>AZMA complies fully with the <strong>Family Educational Rights and Privacy Act (FERPA)</strong>.
                    </p>
                    <p>Under FERPA, students have the right to:</p>
                    <ul>
                        <li>Review and inspect their educational records</li>
                        <li>Request correction of inaccurate records</li>
                        <li>
                            Limit disclosure of personally identifiable information
                        </li>
                    </ul>
                    <p>FERPA permits disclosure of certain “directory information” unless a student requests otherwise
                        in writing.</p>
                    <p class="mt-2">For questions regarding FERPA rights, contact the AZMA administrative office.</p>
                </div>
                <div class="privacyPolicySecs">
                    <h2>7. Data Security</h2>
                    <p>We use industry-standard safeguards to protect your information from unauthorized access or
                        misuse.</p>
                    <p>However, no online system is completely secure. By using our website, you acknowledge that you
                        share information at your own risk.</p>
                </div>
                <div class="privacyPolicySecs">
                    <h2>8. Links to External Sites</h2>
                    <p>Our website may include links to other websites or resources.</p>
                    <p>AZMA is not responsible for the content, privacy practices, or security of those external sites.
                    </p>
                </div>
                <div class="privacyPolicySecs">
                    <h2>9. Your Privacy Rights and Choices</h2>
                    <p>You have the right to:</p>
                    <ul>
                        <li>Request access to, correction, or deletion of your personal information</li>
                        <li>Opt out of receiving promotional emails (by following unsubscribe links)</li>
                        <li>Adjust your browser to limit cookies or tracking</li>
                    </ul>
                    <p>Requests can be submitted to our Privacy Contact (see below).</p>
                </div>
                <div class="privacyPolicySecs">
                    <h2>10. Updates to This Policy</h2>
                    <p>We may revise this Privacy Policy periodically. Any updates will be posted here with an updated
                        “Effective Date.”</p>
                    <p>Your continued use of the website after changes means you accept the updated policy.</p>
                </div>
                <div class="privacyPolicySecs">
                    <h2>11. Contact Us</h2>
                    <p>If you have any questions about this Privacy Policy or how your information is handled, please
                        contact us:</p>
                    <p><strong>Arizona School of Medical Assistant (AZMA)</strong></p>
                    <address>9191 W Thunderbird Rd, Suite 105B, Peoria, AZ 85381
                        (623) 900-7033</address>
                    <a href="https://www.azschoolofmedicalassistant.com">www.azschoolofmedicalassistant.com</a>
                </div>
            </div>
        </div>
    </section>

    <?php
    require_once('footer.php');
  ?>
  <!-- modal pop up -->
  <div id="myModal" class="modal">
  <div class="modal-content">
    <span class="close-modal">&times;</span>
    <h2>Thank you</h2>
      <div id="modalBody"></div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="assets/js/main.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
    crossorigin="anonymous"></script>
    <script type="text/javascript">
       $(document).ready(function () {
        // Open modal
        $('#openModalBtn').click(function () {  
          $('#myModal').fadeIn('show');
        });
        // Close modal on 'x' click
        $('.close-modal').click(function () {
            $('#myModal').fadeOut();
          });
        // Close modal when clicking outside modal content
        $(window).click(function (e) {
            if ($(e.target).is('#myModal')) {
              $('#myModal').fadeOut();
            }
          });
    });
      $("#submit_contact").click(function(){
   
    let is_valid = true;
    var email = document.getElementById('yourEmail');
    var mailFormat = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
    if($("#yourName").val()=='' || $("#yourName").val()==undefined){
            $("#err_yourName").html('<font color="red">Name is required</font>');
        is_valid = false;
    }else{
        $("#err_yourName").html('');
        is_valid = true;
    }
    if($("#yourEmail").val()=='' || $("#yourEmail").val()==undefined){     
        $("#err_yourEmail").html('<font color="red">Email is required</font>');
        is_valid = false;         
    }else{    
      //    console.log("emailVlaid",mailFormat.test($("#yourEmail").val()))
        if(mailFormat.test($("#yourEmail").val())){
           $("#err_yourEmail").html('');
          is_valid_email = true;
        }else{
            $("#err_yourEmail").html('<font color="red">Please provide valid Email</font>');
            is_valid_email = false;         
        }         
    }
    if($("#yourPhone").val()=='' || $("#yourPhone").val()==undefined){     
        $("#err_yourPhone").html('<font color="red">Phone number is required</font>');
        is_valid = false;         
    }else{     
      var number = $("#yourPhone").val();
      var pattern  = /^[0-9()\-\s]+$/;
      console.log("ppp",pattern.test(number));
      
      if(pattern.test(number)){        
          $("#err_yourPhone").html('');
          is_valid_phone = true;
      }else{
         $("#err_yourPhone").html('<font color="red">Please provide valid phone Number</font>');
        is_valid_phone = false;      
      }
     
    }
    
    if($("#yourMessage").val()=='' || $("#yourMessage").val()==undefined){
        //document.querySelector('#yourEmail').style.border = '1px solid';
       // document.querySelector('#yourEmail').style.borderColor = "red";
        $("#err_yourMessage").html('<font color="red">Message is required</font>');
        is_valid = false;
    }else{
       // document.querySelector('#yourEmail').style.border = '';
      //  document.querySelector('#yourEmail').style.borderColor = "";
        $("#err_yourMessage").html('');
        is_valid = true;
    }
    if($("#captcha_input").val()=='' || $("#captcha_input").val()==undefined){
        //document.querySelector('#yourEmail').style.border = '1px solid';
       // document.querySelector('#yourEmail').style.borderColor = "red";
        $("#err_captcha_input").html('<font color="red">Cpatcha is required</font>');
        is_valid = false;
    }else{
       // document.querySelector('#yourEmail').style.border = '';
      //  document.querySelector('#yourEmail').style.borderColor = "";
        $("#err_captcha_input").html('');
        is_valid = true;
    }
    // if($("#yourEmail").val()!=''){  
    //     if(isValidEmail(email)){      
    //        is_valid = true;          
    //     }else{
    //          $("#err_yourEmail").html('<font color="red">Email should be valid.</font>');
    //          is_valid = false;          
    //     }       
    // }
   // return false;
    if(is_valid == false || is_valid_email == false || is_valid_phone == false){
      return false;
    }else{

      // check captcha

      //document.getElementById("request_information").submit();
        data = $("#contact_information").serialize();
        $.ajax({
        url: 'captcha_check.php',       // PHP script to call
        type: 'POST',
        data: data, // send form data
        success: function (response) {
         if(response == '1'){
            document.getElementById("contact_information").submit();
         }else{       
            $("#err_captcha_input").html('<font color="red">Cpatcha is invalid</font>');
         }
          
        },
        error: function () {
          $('#response').html('An error occurred.');
        }
      });
      
     // document.getElementById('contact_information').submit();
      //   data = $("#contact_information").serialize();
      //   $.ajax({
      //   url: 'save_contact.php',       // PHP script to call
      //   type: 'POST',
      //   data: data, // send form data
      //   success: function (response) {
      //     $('#modalBody').html(response); // show result
      //      $('#myModal').fadeIn('show');
      //      document.getElementById('contact_information').reset();
      //   },
      //   error: function () {
      //     $('#response').html('An error occurred.');
      //   }
      // });
    }
 
  })

 $("#subscribeButton").click(function(){
    let yourEmail = $("#subscribeEmail").val();
      if(yourEmail == '' || yourEmail==undefined){
            $("#err_subscribeEmail").html('<font color="red">Email is required</font>');
            is_valid = false;
            return false;         
      }
      if(yourEmail !=''){
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; 
          if(regex.test(yourEmail)==false){
              $("#err_subscribeEmail").html('<font color="red">Email should be valid</font>');
              is_valid = false;
              return false;         
          }else{
              $("#err_subscribeEmail").html('');
          }
      }
       $.ajax({
        url: 'save_contact.php',       // PHP script to call
        type: 'POST',
        data: {"yourEmail":yourEmail,"submit_type":"email_subscription"}, // send form data
        success: function (response) {
          $('#modalBody').html(response); // show result
           $('#myModal').fadeIn('show');
           document.getElementById('subscribeEmail').value ='';
        },
        error: function () {
          $('#response').html('An error occurred.');
        }
      });
  });
  </script>

  <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById("refreshcaptcha").addEventListener("click", function () {
                document.getElementById("captchaimg").src = "captcha.php?" + new Date().getTime();
            });
        });
    </script>
</body>

</html>