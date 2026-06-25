    
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PX4FVPJQ" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

<!-- End Google Tag Manager (noscript) -->

<!-- Google Tag Manager (noscript) -->
<!-- <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KGSQ24Z2"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript> -->
<!-- End Google Tag Manager (noscript) -->
    <?php 

    if (isloggedin() && !isguestuser()) {
        global $USER;
        if (is_siteadmin($USER)) {
             //  echo "admin";
            } elseif (user_has_role_assignment($USER->id, 5)) { // Assuming 5 = student
               // echo "user";
            }
    }


    ?>
    <div class="container">
      <div class="navMain">
        <div class="logo">
             <a href="/echocardiogram" >
          <img src="assets/images/common/logo.png" alt="logo" style="height:77px;">
             </a>
        </div>
        <div class="mobileToggleIcon" id="mobileToggle">
       
          <svg width="34" height="40" viewBox="0 0 34 40" fill="none">
            <path d="M3 2.67236H17H21.6667M3 19.7134H31M3 36.7545H17H22.8333" stroke="#2A3761" stroke-width="5"
              stroke-linecap="round" stroke-linejoin="round" />
          </svg>
   
        </div>

<div class="navItemsMain" id="navMenu">
          <ul class="navItems">
            <!-- <li><a href="<?=$CFG->wwwroot?>/">Home</a></li> -->
              <?php
              if (isloggedin() && !isguestuser() ) {
              ?>
                 <li><a href="<?=$CFG->wwwroot?>/my/courses.php">My Courses</a></li>
              <?php
              }
              ?>
            <!-- <li><a href="<?=$CFG->wwwroot?>/about">About Us</a></li> -->
            <!-- <li><a href="<?=$CFG->wwwroot?>/index-start.php">GETTING STARTED</a></li> -->           
            <!-- <li><a href="<?=$CFG->wwwroot?>/admission">Admission</a></li> -->
            <!-- <li><a href="<?=$CFG->wwwroot?>/programs">Programs</a></li> -->
            <!-- <li><a href="<?=$CFG->wwwroot?>/contact">Contact</a></li> -->
             <?php
              if (isloggedin() && !isguestuser() ) {
              ?>
                 <li><a href="<?=$CFG->wwwroot?>/dashboard/index.php">My Dashboard</a></li>
              <?php
              }
              ?>
          </ul>
          <!-- <button class="studentCornerBtn scheduleNowBtn" onClick="window.location.href='https://calendly.com/francisco-azschoolofmedicalassistant/30min'">Schedule Now <span> -->
              <!-- <svg width="30" height="30" viewBox="0 0 30 30" fill="none">
                <path
                  d="M27.5 11.25H2.5V7.5C2.5 6.50544 2.89509 5.55161 3.59835 4.84835C4.30161 4.14509 5.25544 3.75 6.25 3.75H23.75C24.7446 3.75 25.6984 4.14509 26.4017 4.84835C27.1049 5.55161 27.5 6.50544 27.5 7.5V11.25Z"
                  fill="#2A3761" />
                <path
                  d="M2.5 23.75C2.50099 24.7443 2.8964 25.6975 3.59945 26.4006C4.30249 27.1036 5.25574 27.499 6.25 27.5H23.75C24.7443 27.499 25.6975 27.1036 26.4006 26.4006C27.1036 25.6975 27.499 24.7443 27.5 23.75V11.25H2.5V23.75Z"
                  fill="#B4C0E7" />
                <path
                  d="M8.75 8.75C8.41848 8.75 8.10054 8.6183 7.86612 8.38388C7.6317 8.14946 7.5 7.83152 7.5 7.5V2.5C7.5 2.16848 7.6317 1.85054 7.86612 1.61612C8.10054 1.3817 8.41848 1.25 8.75 1.25C9.08152 1.25 9.39946 1.3817 9.63388 1.61612C9.8683 1.85054 10 2.16848 10 2.5V7.5C10 7.83152 9.8683 8.14946 9.63388 8.38388C9.39946 8.6183 9.08152 8.75 8.75 8.75ZM21.25 8.75C20.9185 8.75 20.6005 8.6183 20.3661 8.38388C20.1317 8.14946 20 7.83152 20 7.5V2.5C20 2.16848 20.1317 1.85054 20.3661 1.61612C20.6005 1.3817 20.9185 1.25 21.25 1.25C21.5815 1.25 21.8995 1.3817 22.1339 1.61612C22.3683 1.85054 22.5 2.16848 22.5 2.5V7.5C22.5 7.83152 22.3683 8.14946 22.1339 8.38388C21.8995 8.6183 21.5815 8.75 21.25 8.75Z"
                  fill="#B4C0E7" />
                <path
                  d="M8.75 17.5C9.44036 17.5 10 16.9404 10 16.25C10 15.5596 9.44036 15 8.75 15C8.05964 15 7.5 15.5596 7.5 16.25C7.5 16.9404 8.05964 17.5 8.75 17.5Z"
                  fill="#2A3761" />
                <path
                  d="M21.25 17.5C21.9404 17.5 22.5 16.9404 22.5 16.25C22.5 15.5596 21.9404 15 21.25 15C20.5596 15 20 15.5596 20 16.25C20 16.9404 20.5596 17.5 21.25 17.5Z"
                  fill="#9AA4C4" />
                <path
                  d="M15 17.5C15.6904 17.5 16.25 16.9404 16.25 16.25C16.25 15.5596 15.6904 15 15 15C14.3096 15 13.75 15.5596 13.75 16.25C13.75 16.9404 14.3096 17.5 15 17.5Z"
                  fill="#2A3761" />
                <path
                  d="M15 22.5C15.6904 22.5 16.25 21.9404 16.25 21.25C16.25 20.5596 15.6904 20 15 20C14.3096 20 13.75 20.5596 13.75 21.25C13.75 21.9404 14.3096 22.5 15 22.5Z"
                  fill="#9AA4C4" />
                <path
                  d="M8.75 22.5C9.44036 22.5 10 21.9404 10 21.25C10 20.5596 9.44036 20 8.75 20C8.05964 20 7.5 20.5596 7.5 21.25C7.5 21.9404 8.05964 22.5 8.75 22.5Z"
                  fill="#2A3761" />
                <path
                  d="M21.25 22.5C21.9404 22.5 22.5 21.9404 22.5 21.25C22.5 20.5596 21.9404 20 21.25 20C20.5596 20 20 20.5596 20 21.25C20 21.9404 20.5596 22.5 21.25 22.5Z"
                  fill="#2A3761" />
              </svg> -->
            </span></button>
          <!-- <button class="studentCornerBtn" onClick="window.location.href='<?=$CFG->wwwroot?>/student-portal'">Student Portal <span>
              <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                <path d="M1 6H11" stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M6 1L11 6L6 11" stroke="white" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </span></button> -->
        </div>
     </div>
    </div>