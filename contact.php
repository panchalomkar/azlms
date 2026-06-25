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

  <section class="heroSection aboutHeroSection">
    <div class="container position-relative">
      <div class="d-flex justify-content-between align-items-center">
        <div class="heroContent">
          <h1> Contact Us </h1>
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
      <div class="contactUsImgSec">
        <img src="/assets/images/common/doctors-posing-blue.png" alt="doctors posing" class="img-fluid">
      </div>
      <div class="contactUsFormSecMain">
        <div class="row g-5">
          <div class="col-md-6">
            <div class="haveEnquireSecC">
              <div class="haveEnquireSecCHead">
                <h2>Have inquiries? Contact us!</h2>
                <p>We are here to assist you with any questions or concerns you may have. Feel free to
                  reach out to us anytime.</p>
              </div>
              <div class="addrSecMain">
                <div class="addrSecC">
                  <h3>Arizona School of Medical Assistant</h3>
                  <address>9191 W Thunderbird Rd, Suite 105B, Peoria, AZ 85381
                    </address>
                </div>
                <!-- <div class="addrSecC">
                  <h3>Other Locations</h3>
                  <address>3124 Willow Creek Road, Prescott, AZ 86301</address>
                  <address>1254 W. University Ave., Suite 101, Flagstaff, AZ 86001</address>
                  <address>1833 W. Main St., Suite 131, Mesa, AZ 85201</address>
                </div> -->
                <div class="CEPMain">
                  <svg width="64" height="64" viewBox="0 0 64 64" fill="none">
                    <path
                      d="M8.76023 21.1L8.70023 36.81H7.52023C6.57932 36.7882 5.66254 36.508 4.87023 36C4.29698 35.642 3.76683 35.2192 3.29023 34.74C2.51009 33.936 1.82246 33.0471 1.24023 32.09V25.8C1.82935 24.8453 2.52369 23.9598 3.31023 23.16C3.78995 22.6801 4.32348 22.2573 4.90023 21.9C5.50893 21.5146 6.1892 21.2561 6.90023 21.14C7.10996 21.1248 7.32051 21.1248 7.53023 21.14L8.76023 21.1ZM62.7602 26V32.29C62.1711 33.2446 61.4768 34.1301 60.6902 34.93C60.046 35.5797 59.2951 36.1141 58.4702 36.51C57.8447 36.8063 57.1623 36.9633 56.4702 36.97H55.4702C54.37 38.6053 53.0774 40.1027 51.6202 41.43C51.3536 41.67 51.0836 41.9033 50.8102 42.13C49.1295 43.5186 47.2525 44.6509 45.2402 45.49L44.8802 45.64C43.3089 46.2724 41.6722 46.7285 40.0002 47L39.2502 47.13L38.7302 47.71C38.4798 47.9947 38.1724 48.2237 37.8279 48.3822C37.4834 48.5407 37.1094 48.6251 36.7302 48.63H29.5902C28.8688 48.63 28.177 48.3434 27.6669 47.8333C27.1568 47.3232 26.8702 46.6314 26.8702 45.91C26.8702 45.1886 27.1568 44.4967 27.6669 43.9866C28.177 43.4765 28.8688 43.19 29.5902 43.19H36.7302C37.2983 43.1941 37.8508 43.3759 38.3102 43.71L39.0502 44.24L39.9602 44.08C42.3849 43.6622 44.7069 42.7829 46.8002 41.49C46.8702 41.49 47.5002 41.04 47.7402 40.87C49.8275 39.3397 51.6113 37.4338 53.0002 35.25L53.2202 34.92C53.9778 33.7137 54.6463 32.4537 55.2202 31.15V21.27H56.4002H56.8202C57.2166 21.3117 57.6062 21.4024 57.9802 21.54C58.9824 21.9384 59.8865 22.549 60.6302 23.33C61.4396 24.136 62.1542 25.0318 62.7602 26Z"
                      fill="#B4C0E7" />
                    <path
                      d="M61.8298 54.84C61.1899 54.1397 60.6142 53.3834 60.1098 52.58C57.8198 48.58 57.7398 43.94 57.9998 39.42C58.0055 39.3256 57.99 39.2312 57.9542 39.1437C57.9185 39.0562 57.8636 38.9779 57.7935 38.9145C57.7234 38.8511 57.6399 38.8043 57.5492 38.7776C57.4586 38.7508 57.3631 38.7448 57.2698 38.76C56.9882 38.7993 56.7042 38.8193 56.4198 38.82C55.3703 40.2724 54.174 41.6128 52.8498 42.82C52.5998 43.05 52.2998 43.31 51.9898 43.57C50.179 45.0707 48.1574 46.2972 45.9898 47.21L45.5998 47.37C45.0598 47.58 44.5098 47.78 43.9698 47.95C40.8898 52.5 36.7898 54.8 31.7798 54.8C24.3398 54.8 18.8898 48.33 16.7798 41.95C15.9004 39.1851 15.4518 36.3013 15.4498 33.4C15.4498 30.21 15.5898 26.1 17.1898 22.58C18.3541 20.0972 20.3097 18.0712 22.7498 16.82C25.1679 15.5023 27.9812 15.1045 30.6698 15.7C31.433 15.8852 32.1716 16.1604 32.8698 16.52C33.9798 17.09 34.7098 18.52 35.6398 20.36C37.4698 23.97 39.9798 28.91 47.2898 31.74C47.7498 31.92 49.2198 32.55 49.1198 33.29C48.8783 34.9737 48.5444 36.6429 48.1198 38.29C49.4566 37.1186 50.6304 35.7733 51.6098 34.29L51.7898 34.01C52.4376 32.9868 53.0156 31.9211 53.5198 30.82V19.5H56.5198C56.6193 19.5026 56.718 19.4815 56.8078 19.4386C56.8976 19.3957 56.9759 19.3321 57.0364 19.2531C57.0969 19.1741 57.1378 19.0819 57.1558 18.984C57.1737 18.8861 57.1683 18.7853 57.1398 18.69C56.4741 16.3714 55.4118 14.1858 53.9998 12.23C50.6816 7.7986 46.053 4.52391 40.7698 2.86998C36.7928 1.53486 32.5744 1.07419 28.4029 1.51947C24.2315 1.96475 20.2054 3.30546 16.5998 5.44998C11.6594 8.4252 7.9297 13.051 6.0698 18.51C6.03245 18.6122 6.02222 18.7224 6.04011 18.8297C6.058 18.9371 6.10339 19.038 6.17188 19.1226C6.24036 19.2072 6.32959 19.2726 6.43087 19.3124C6.53216 19.3523 6.64204 19.3652 6.7498 19.35C7.02475 19.3058 7.30178 19.2758 7.5798 19.26H10.5798L10.5098 38.64H7.5098C7.17463 38.6362 6.84027 38.6061 6.5098 38.55C6.40675 38.5321 6.30085 38.5401 6.20166 38.5733C6.10248 38.6066 6.01313 38.664 5.94169 38.7404C5.87024 38.8168 5.81895 38.9098 5.79243 39.011C5.76591 39.1122 5.76501 39.2184 5.7898 39.32C6.1598 40.92 6.6098 42.52 7.1298 44.09V44.15V44.5C7.4398 48 4.3998 51.23 4.3698 51.26L4.0498 51.6H4.5098C6.13073 51.4689 7.63702 50.7122 8.7098 49.49L8.9998 49.14L9.2098 49.67C11.3098 54.85 8.2098 61.4 8.2098 61.46L8.0098 61.88L8.4298 61.7C16.3098 58.28 14.6798 49.42 14.6598 49.33C14.5898 49.01 14.4198 48.15 14.2498 46.78L14.0898 45.39L14.7098 46.64C15.4643 48.1942 16.3686 49.6712 17.4098 51.05C18.9952 53.2484 21.0635 55.0541 23.4559 56.3283C25.8482 57.6025 28.5008 58.3112 31.2098 58.4H32.1398C34.8499 58.3338 37.5088 57.6467 39.9116 56.3915C42.3145 55.1363 44.3973 53.3466 45.9998 51.16C46.677 50.282 47.295 49.36 47.8498 48.4L48.2498 47.72V48.51C48.3556 51.4019 49.2341 54.2126 50.794 56.65C52.3539 59.0875 54.5382 61.0625 57.1198 62.37L57.5798 62.62L57.3598 62.15C57.2198 61.86 54.0298 54.95 54.2798 50.36V49.65L54.6798 50.24C56.1098 52.21 59.9998 54 61.8298 54.84Z"
                      fill="#2A3761" />
                  </svg>
                  <div class="ContactGCNow">
                    <h3>Call us Now</h3>
                    <a href="tel:6239007033">(623) 900-7033</a>
                  </div>
                </div>
                <div class="CEPMain">
                  <svg width="73" height="73" viewBox="0 0 73 73" fill="none">
                    <path
                      d="M5.03467 18.6779V54.3522L22.8718 36.515L5.03467 18.6779ZM8.57894 15.1034L31.1611 37.6856C34.0132 40.5376 38.9872 40.5376 41.8393 37.6856L64.4214 15.1034H8.57894Z"
                      fill="#B4C0E7" />
                    <path
                      d="M45.3984 41.245C43.0246 43.6213 39.863 44.9328 36.4999 44.9328C33.1369 44.9328 29.9752 43.6213 27.6015 41.245L26.431 40.0745L8.60889 57.8966H64.391L46.5689 40.0745L45.3984 41.245ZM50.1283 36.5152L67.9654 54.3523V18.678L50.1283 36.5152Z"
                      fill="#2A3761" />
                  </svg>
                  <div class="ContactGCNow">
                    <h3>Email Us</h3>
                    <a href="mailto:support@azschoolofmedicalassistant.com">mailto:support@azschoolofmedicalassistant.com</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <form method="post" id = "contact_information" name="contact_information" action = "/thankyou-contact">
              <div class="row gx-5 gy-2">
                <div class="formGroup contactFormGroup col-md-6">
                  <label for="yourName" class="form-label">Full Name</label>
                  <input type="text" id="yourName" name="yourName" aria-describedby="emailHelp">
                   <span id="err_yourName" ></span>
                </div>
                <div class="formGroup contactFormGroup col-md-6">
                  <label for="yourPhone" class="form-label">Phone Number</label>
                  <input type="text" id="yourPhone" name="yourPhone">
                   <span id="err_yourPhone" ></span>
                </div>
                <div class="formGroup contactFormGroup col-md-6">
                  <label for="yourEmail" class="form-label">Email Address</label>
                  <input type="email" id="yourEmail" name="yourEmail">
                  <span id="err_yourEmail" ></span>
                </div>
                <div class="formGroup contactFormGroup col-md-12">
                  <label for="yourMessage" class="form-label">Message</label>
                  <textarea id="yourMessage" name="yourMessage"></textarea>
                  <span id="err_yourMessage" ></span>
                </div>
                <input type = "hidden" name="submit_type" id ="submit_type" value="contact_info" />
              </div>
               <div class="formGroup contactFormGroup col-md-12">
                   <!-- <img src="captcha.php" height='60' width = '300' alt="CAPTCHA Image">
                   <label for="captcha" class="form-label">Enter the text shown above:</label>                 
                  <input type="text" name="captcha_input" id = "captcha_input" required>
                   <span id="err_captcha_input" ></span> -->

                   <div class="d-flex align-items-center gap-2 my-2">
                      <img src="captcha.php" id="captchaimg" height="60" width="200" alt="CAPTCHA Image" class="img-thumbnail">
                      <button type="button" id="refreshcaptcha" class="btn btn-outline-secondary">↻</button>
                  </div>
                  <label for="captcha_input" class="form-label">Enter the text shown above:</label>
                  <input type="text" name="captcha_input" id="captcha_input" class="form-control" required>
                  <span id="err_captcha_input" class="text-danger small"></span>  
              </div>


              <div class="col-md-12 mt-2">
                  <div class="form-check customCheckbox">
                    <input class="form-check-input" type="checkbox" value="" id="msg_disclosure">
                    <label class="form-check-label" for="msg_disclosure">
                      By providing your phone number, you agree to receive text messages from the Arizona School of
                      Medical Assistant (AZMA) about programs, admissions, and school updates. Message and data rates
                      may apply. Message frequency may vary. Consent is not required for enrollment. Reply STOP to
                      unsubscribe or HELP for assistance. See our <a href='/privacy-policy' class="text-decoration-underline" target="_blank">Privacy Policy</a> for details.
                    </label>                    
                  </div>
                  <span id="err_msg_disclosure_input" class="text-danger small"></span>
                </div>


               <!-- <div class="formGroup contactFormGroup col-md-12"> -->
                   <!-- <img src="captcha.php" height='60' width = '300' alt="CAPTCHA Image">
                   <label for="captcha" class="form-label">Enter the text shown above:</label>                 
                  <input type="text" name="captcha_input" id = "captcha_input" required>
                   <span id="err_captcha_input" ></span> -->

                  <!-- <div class="form-check customCheckbox mt-4">
                                <input class="form-check-input" type="checkbox" value="" id="msg_disclosure" name="msg_disclosure">
                                <label class="form-check-label" for="msg_disclosure">
                                    By providing your phone number, you agree to receive text messages from the 
                      <strong>Arizona School of Medical Assistant (AZMA)</strong> about programs, admissions, and school updates. 
                      Message and data rates may apply. Message frequency may vary. 
                      Consent is not required for enrollment. Reply <strong>STOP</strong> to unsubscribe or <strong>HELP</strong> for assistance. 
                      See our <a href="/privacy-policy" target="_blank">Privacy Policy</a> for details.
                                </label>
                                 
                            </div>
                       <span id="err_msg_disclosure_input" class="text-danger small"></span> -->
                  <!-- <div class="mb-4">
                  <div class="form-check customCheckbox">
                   <input  class= "form-check-input" type="checkbox" id="msg_disclosure" name="msg_disclosure" value="1" />
                    <label class="form-check-label" for="msg_disclosure">
                      By providing your phone number, you agree to receive text messages from the 
                      <strong>Arizona School of Medical Assistant (AZMA)</strong> about programs, admissions, and school updates. 
                      Message and data rates may apply. Message frequency may vary. 
                      Consent is not required for enrollment. Reply <strong>STOP</strong> to unsubscribe or <strong>HELP</strong> for assistance. 
                      See our <a href="/privacy-policy" target="_blank">Privacy Policy</a> for details.
                    </label>
                  </div>
                  <span id="err_msg_disclosure_input" class="text-danger small"></span>
                </div> -->

              <button type="button" id="submit_contact" class="submitContactBtn">Submit <span>
                  <svg width="54" height="54" viewBox="0 0 54 54" fill="none">
                    <path
                      d="M29.2504 38.2499C28.8035 38.2525 28.3659 38.122 27.9935 37.8749C27.6211 37.6279 27.3307 37.2755 27.1593 36.8628C26.9879 36.45 26.9434 35.9956 27.0313 35.5574C27.1192 35.1192 27.3356 34.7171 27.6529 34.4024L35.0779 26.9999L27.6529 19.5974C27.2843 19.167 27.0917 18.6133 27.1135 18.0471C27.1354 17.4808 27.3702 16.9436 27.7709 16.5429C28.1716 16.1422 28.7087 15.9075 29.275 15.8856C29.8413 15.8637 30.395 16.0563 30.8254 16.4249L39.8254 25.4249C40.2445 25.8465 40.4797 26.4168 40.4797 27.0112C40.4797 27.6056 40.2445 28.1759 39.8254 28.5974L30.8254 37.5974C30.4063 38.0131 29.8407 38.2475 29.2504 38.2499Z"
                      fill="white" />
                    <path
                      d="M38.25 29.25H15.75C15.1533 29.25 14.581 29.0129 14.159 28.591C13.7371 28.169 13.5 27.5967 13.5 27C13.5 26.4033 13.7371 25.831 14.159 25.409C14.581 24.9871 15.1533 24.75 15.75 24.75H38.25C38.8467 24.75 39.419 24.9871 39.841 25.409C40.2629 25.831 40.5 26.4033 40.5 27C40.5 27.5967 40.2629 28.169 39.841 28.591C39.419 29.0129 38.8467 29.25 38.25 29.25Z"
                      fill="white" />
                  </svg>
                </span>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="googleMapSection sectionGap">
    <div class="container">
      <div class="mapContainerB">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d53461.03515454203!2d-112.2105956!3d33.4483771!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x872b0b6f25b1a3a9%3A0xa67a98b399da2a7b!2sPhoenix%2C%20AZ!5e0!3m2!1sen!2sus!4v1696764361843!5m2!1sen!2sus"
          width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
    </div>
  </section>
  <section class="subscribeSection sectionGap">
    <div class="container">
      <div class="row gx-5 gy-lg-0 gy-5 align-items-center">
        <div class="col-lg-6">
          <div class="subscribeLeft">
            <h2>Subscribe to our Newsletter</h2>
            <p>Stay informed about the latest admissions announcements, academic programs, research breakthroughs, and
              campus news by subscribing to our newsletter.</p>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="subscribeRight">
            <form>
              <div class="input-group subscribeInputBtn">
                <input name="subscribeEmail" id="subscribeEmail" type="email" placeholder="Enter your email" aria-label="Email"
                  aria-describedby="subscribeButton">
                <button class="subscribeButton" type="button" id="subscribeButton">Subscribe</button>
                <span id="err_subscribeEmail" /></span>
              </div>
            </form>
          </div>
        </div>
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
      //  alert($("#msg_disclosure").prop("checked"));
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
        $("#err_captcha_input").html('<font color="red">Captcha is required</font>');
        is_valid = false;
    }else{
       // document.querySelector('#yourEmail').style.border = '';
      //  document.querySelector('#yourEmail').style.borderColor = "";
        $("#err_captcha_input").html('');
        is_valid = true;
    }
    if($("#msg_disclosure").prop("checked") == false){
         $("#err_msg_disclosure_input").html('<font color="red">should be checked</font>');
        is_valid = false;
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