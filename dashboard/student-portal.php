<!DOCTYPE html>
<html lang="en">
<head>
<?php 
// Get login token
  require_once('header.php');
 ?>
</head>
<?php
require_once('config.php');
require_once($CFG->libdir . '/formslib.php');
$logintoken = \core\session\manager::get_login_token();

//require_capability('moodle/course:view', context_system::instance());
global $DB;
$courses = $DB->get_records_sql("SELECT * FROM {course} WHERE id != 1 ORDER BY fullname ASC");

require_once($CFG->libdir . '/authlib.php');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Please enter username and password.';
    } else {
        // Authenticate user
        $user = authenticate_user_login($username, $password);
        if ($user) {
            // Complete the login process (set session etc)
            complete_user_login($user);

            // Redirect to homepage or dashboard
            redirect(new moodle_url('/dashboard/'));
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}

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
                    <h1>Student Corner</h1>
                </div>
                <div>
                    <svg width="180" height="180" viewBox="0 0 180 180" fill="none">
                        <path
                            d="M174.744 126.576V149.508C174.744 151.488 173.16 153.108 171.144 153.108H159.948V172.8C159.948 174.78 158.328 176.4 156.348 176.4C154.368 176.4 152.748 174.78 152.748 172.8V153.108H27.2879V172.8C27.2879 174.78 25.6681 176.4 23.6879 176.4C21.6717 176.4 20.0879 174.78 20.0879 172.8V153.108H8.85635C6.87617 153.108 5.25635 151.488 5.25635 149.508V126.576C5.25635 124.596 6.87617 122.976 8.85635 122.976H171.144C173.16 122.976 174.744 124.596 174.744 126.576ZM62.5681 32.7239C62.5681 15.0479 73.296 3.59998 89.9276 3.59998C106.56 3.59998 117.288 15.0479 117.288 32.7239C117.288 51.8399 105.012 67.3919 89.9276 67.3919C74.8438 67.3919 62.5681 51.8398 62.5681 32.7239ZM28.6563 112.5V105.12C28.6563 98.8198 32.3996 93.1319 38.16 90.6479C72.6843 73.9799 107.892 73.9079 142.02 90.7198C147.637 93.1319 151.38 98.8198 151.38 105.12V112.5C151.38 114.3 149.904 115.776 148.104 115.776H31.9321C30.1321 115.776 28.6563 114.3 28.6563 112.5Z"
                            fill="white" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-shape aboutHeroBg1"></div>
    </section>
    <section class="SCCourseLoginSec sectionGap mb-5">
        <div class="container">
            <div class="row gx-3 gy-md-0 gy-3">
                <div class="col-md-6 col-lg-5">
                    <div class="SCCourseHead">
                        <span>
                            <svg width="60" height="60" viewBox="0 0 60 60" fill="none">
                                <g clip-path="url(#clip0_292_628)">
                                    <path
                                        d="M30 60C46.5685 60 60 46.5685 60 30C60 13.4315 46.5685 0 30 0C13.4315 0 0 13.4315 0 30C0 46.5685 13.4315 60 30 60Z"
                                        fill="#4E5D8D" />
                                    <path
                                        d="M37.9914 58.9154C38.0022 58.9115 38.013 58.9077 38.0237 58.9038C38.9881 58.6377 39.9385 58.3234 40.8714 57.9623C41.8028 57.5996 42.7155 57.1907 43.606 56.7369C44.4973 56.2829 45.3653 55.7846 46.2068 55.2438C47.0484 54.7041 47.8625 54.1226 48.646 53.5015C49.4301 52.8795 50.1826 52.2187 50.9007 51.5215C51.6184 50.8245 52.3008 50.0921 52.9453 49.3269C53.5893 48.5615 54.1947 47.7643 54.7591 46.9384C55.3245 46.1128 55.8482 45.2595 56.3284 44.3815C56.8085 43.5039 57.2444 42.6027 57.6345 41.6815C57.9411 40.936 58.2174 40.1785 58.463 39.4107L34.9176 15.8607C34.2807 15.2218 33.524 14.715 32.6908 14.3693C31.8576 14.0235 30.9643 13.8458 30.0622 13.8461H29.9376C28.1187 13.8486 26.3751 14.5722 25.089 15.8583C23.8029 17.1444 23.0793 18.8881 23.0768 20.7069V25.4469C20.2937 27.5584 18.4614 30.8631 18.4614 34.6154C18.4614 37.7954 19.756 40.68 21.8468 42.7684L37.9914 58.9131V58.9154Z"
                                        fill="#344479" />
                                </g>
                                <path
                                    d="M29.5 12L9 22.277L11.9286 24.2055V42.75L14.8571 44.2143V26.0799L29.4865 35.4286L41.58 27.6083L50 22.1335L29.5 12Z"
                                    fill="white" />
                                <path
                                    d="M41.7754 29.5714L29.5001 37.6037L17.1023 29.5714L16.3215 37.0327C17.7858 38.1726 26.5715 44.2142 29.5003 47.1428C32.4287 44.2142 41.2144 38.1769 42.6787 37.0356L41.7754 29.5714Z"
                                    fill="white" />
                                <defs>
                                    <clipPath id="clip0_292_628">
                                        <rect width="60" height="60" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>

                        </span>
                        <h3>Courses</h3>
                    </div>
                    <div class="row g-4">
                        <?php 
                        $i=1;
                        $a = array(1,4,5,8,9,12);
                        foreach($courses as $course_details){
                            if(in_array($i,$a)){
                                $class = "SCCourseBox1";
                            }else{
                                 $class = "SCCourseBox2";
                            }
                        ?>
                        <div class="col-md-6 col-6">
                            <div class="<?php echo $class;?>">
                                <p><?php echo $course_details->fullname;?></p>
                            </div>
                        </div>
                        <?php
                            $i++;
                         
                        } 
                        ?>
                        <!-- <div class="col-md-6 col-6">
                            <div class="SCCourseBox2">
                                <p>MA SkillsBuilder™ <br>
                                    : Clinical Plus 2.0</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-6">
                            <div class="SCCourseBox2">
                                <p>MA SkillsBuilder™ <br>
                                    : Administrative Plus</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-6">
                            <div class="SCCourseBox1">
                                <p>Principles of Health Coaching 1.1 <span>
                                        <svg width="54" height="54" viewBox="0 0 54 54" fill="none">
                                            <path
                                                d="M29.2499 38.25C28.803 38.2526 28.3654 38.1221 27.993 37.875C27.6206 37.6279 27.3302 37.2756 27.1588 36.8628C26.9874 36.4501 26.9429 35.9957 27.0308 35.5575C27.1187 35.1193 27.3351 34.7172 27.6524 34.4025L35.0774 27L27.6524 19.5975C27.2838 19.1671 27.0912 18.6134 27.1131 18.0471C27.1349 17.4809 27.3697 16.9437 27.7704 16.543C28.1711 16.1423 28.7083 15.9075 29.2745 15.8857C29.8408 15.8638 30.3945 16.0564 30.8249 16.425L39.8249 25.425C40.244 25.8466 40.4792 26.4168 40.4792 27.0113C40.4792 27.6057 40.244 28.1759 39.8249 28.5975L30.8249 37.5975C30.4058 38.0132 29.8402 38.2475 29.2499 38.25Z"
                                                fill="white" />
                                            <path
                                                d="M38.25 29.25H15.75C15.1533 29.25 14.581 29.0129 14.159 28.591C13.7371 28.169 13.5 27.5967 13.5 27C13.5 26.4033 13.7371 25.831 14.159 25.409C14.581 24.9871 15.1533 24.75 15.75 24.75H38.25C38.8467 24.75 39.419 24.9871 39.841 25.409C40.2629 25.831 40.5 26.4033 40.5 27C40.5 27.5967 40.2629 28.169 39.841 28.591C39.419 29.0129 38.8467 29.25 38.25 29.25Z"
                                                fill="white" />
                                        </svg></span></p>
                            </div>
                        </div>
                        <div class="col-md-6 col-6">
                            <div class="SCCourseBox1">
                                <p>Principles of Health Coaching 1.1</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-6">
                            <div class="SCCourseBox2">
                                <p>Medical Assistant (CCMA) Online Study Guide 3.0</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-6">
                            <div class="SCCourseBox2">
                                <p>Medical Terminology 2.0</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-6">
                            <div class="SCCourseBox1">
                                <p>Medical Assistant (CCMA) Online Study Guide 3.0</p>
                            </div>
                        </div> -->
                    </div>
                </div>
                <div class="col-lg-2 d-none d-lg-flex align-items-start justify-content-center SCorndividerCol">
                    <svg width="7" height="538" viewBox="0 0 7 638" fill="none">
                        <line x1="3.5" y1="1.5299e-07" x2="3.49997" y2="638" stroke="url(#paint0_linear_97_2258)"
                            stroke-width="7" />
                        <defs>
                            <linearGradient id="paint0_linear_97_2258" x1="-0.5" y1="-2.18557e-08" x2="-0.500028"
                                y2="638" gradientUnits="userSpaceOnUse">
                                <stop offset="0.0432692" stop-color="white" />
                                <stop offset="0.495192" stop-color="#435180" />
                                <stop offset="0.981191" stop-color="#666666" stop-opacity="0" />
                            </linearGradient>
                        </defs>
                    </svg>

                </div>
                <div class="col-md-6 col-lg-5 SCornLoginSecCo">
                    <div class="backgroundCenterImage"></div>
                    <div class="SCCourseHead">
                        <span>
                            <svg width="60" height="60" viewBox="0 0 60 60" fill="none">
                                <g clip-path="url(#clip0_291_597)">
                                    <path
                                        d="M30 60C46.5685 60 60 46.5685 60 30C60 13.4315 46.5685 0 30 0C13.4315 0 0 13.4315 0 30C0 46.5685 13.4315 60 30 60Z"
                                        fill="#4E5D8D" />
                                    <path
                                        d="M37.9914 58.9154C38.0022 58.9115 38.013 58.9077 38.0237 58.9038C38.9881 58.6377 39.9385 58.3234 40.8714 57.9623C41.8028 57.5996 42.7155 57.1907 43.606 56.7369C44.4973 56.2829 45.3653 55.7846 46.2068 55.2438C47.0484 54.7041 47.8625 54.1226 48.646 53.5015C49.4301 52.8795 50.1826 52.2187 50.9007 51.5215C51.6184 50.8245 52.3008 50.0921 52.9453 49.3269C53.5893 48.5615 54.1947 47.7643 54.7591 46.9384C55.3245 46.1128 55.8482 45.2595 56.3284 44.3815C56.8085 43.5039 57.2444 42.6027 57.6345 41.6815C57.9411 40.936 58.2174 40.1785 58.463 39.4107L34.9176 15.8607C34.2807 15.2218 33.524 14.715 32.6908 14.3693C31.8576 14.0235 30.9643 13.8458 30.0622 13.8461H29.9376C28.1187 13.8486 26.3751 14.5722 25.089 15.8583C23.8029 17.1444 23.0793 18.8881 23.0768 20.7069V25.4469C20.2937 27.5584 18.4614 30.8631 18.4614 34.6154C18.4614 37.7954 19.756 40.68 21.8468 42.7684L37.9914 58.9131V58.9154Z"
                                        fill="#344479" />
                                    <path
                                        d="M29.9376 13.8461C28.1187 13.8486 26.3751 14.5722 25.089 15.8583C23.8029 17.1444 23.0793 18.8881 23.0768 20.7069V25.4469C20.2937 27.5584 18.4614 30.8631 18.4614 34.6154C18.4614 40.9754 23.6399 46.1538 29.9999 46.1538C36.3599 46.1538 41.5383 40.9754 41.5383 34.6154C41.5383 30.8654 39.7107 27.5584 36.9276 25.4469V20.7069C36.9233 18.8878 36.1985 17.1445 34.9118 15.8587C33.625 14.5728 31.8813 13.8492 30.0622 13.8461H29.9376ZM29.9376 16.1538H30.0622C32.603 16.1538 34.6153 18.1661 34.6153 20.7069V24.0531C33.1614 23.4103 31.5895 23.0778 29.9999 23.0769C28.3568 23.0769 26.8014 23.4323 25.3845 24.0554V20.7069C25.3845 18.1661 27.3968 16.1538 29.9376 16.1538ZM29.9999 25.3846C30.563 25.3846 31.1076 25.4515 31.6383 25.5461C31.8276 25.5807 32.0099 25.6269 32.1945 25.6731C32.536 25.7561 32.866 25.86 33.1914 25.98C33.3968 26.0561 33.6045 26.1277 33.803 26.2177C34.1745 26.3861 34.5299 26.5823 34.8714 26.7946C34.9568 26.8477 35.0468 26.8869 35.1299 26.9446C35.1517 26.9584 35.1741 26.9715 35.1968 26.9838C36.4416 27.8294 37.4602 28.9672 38.1634 30.2976C38.8667 31.628 39.2331 33.1105 39.2307 34.6154C39.2307 39.7269 35.1114 43.8461 29.9999 43.8461C24.8883 43.8461 20.7691 39.7269 20.7691 34.6154C20.7671 33.1157 21.1315 31.6383 21.8305 30.3115C22.5294 28.9847 23.5419 27.8487 24.7799 27.0023C24.8123 26.9846 24.8439 26.9653 24.8745 26.9446C25.0014 26.8592 25.1376 26.8015 25.2668 26.7231C25.5622 26.5454 25.8576 26.3723 26.1737 26.2269C26.3883 26.1277 26.6122 26.0515 26.836 25.9707C27.1453 25.8554 27.4614 25.7584 27.7868 25.6777C28.5099 25.4904 29.253 25.392 29.9999 25.3846ZM29.9999 30C28.103 30 26.5383 31.5646 26.5383 33.4615C26.5383 34.41 26.9283 35.2846 27.5537 35.9077C27.9114 36.2677 28.3568 36.5377 28.846 36.7107V41.5384H31.1537V36.7107C31.6453 36.5377 32.093 36.2677 32.453 35.91C32.9377 35.4256 33.2677 34.8082 33.4012 34.1361C33.5348 33.464 33.4658 32.7674 33.2031 32.1345C32.9405 31.5016 32.4958 30.9609 31.9256 30.5809C31.3553 30.2009 30.6851 29.9988 29.9999 30ZM29.9999 32.3077C30.6599 32.3077 31.1537 32.8015 31.1537 33.4615C31.1553 33.6135 31.1265 33.7642 31.069 33.9049C31.0116 34.0456 30.9267 34.1734 30.8192 34.2808C30.7118 34.3883 30.584 34.4732 30.4433 34.5307C30.3026 34.5881 30.1518 34.6169 29.9999 34.6154C29.8479 34.6169 29.6972 34.5881 29.5565 34.5307C29.4158 34.4732 29.288 34.3883 29.1806 34.2808C29.0731 34.1734 28.9882 34.0456 28.9307 33.9049C28.8733 33.7642 28.8445 33.6135 28.846 33.4615C28.846 32.8015 29.3399 32.3077 29.9999 32.3077Z"
                                        fill="white" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_291_597">
                                        <rect width="60" height="60" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </span>
                        <h3>Login</h3>
                    </div>
                    <div class="row g-5"> 
                        <form method="post" action="<?php echo $CFG->wwwroot; ?>/student-portal.php">
                            <div class="row gy-2">
                                <div class="formGroup contactFormGroup">
                                    <label for="yourName" class="form-label">Email Address</label>
                                    <input type="text" name="username" placeholder="Username" required >
                                </div>
                                <div class="formGroup contactFormGroup">
                                    <label for="yourPhone" class="form-label">Password</label>
                                    <input type="password" name="password" placeholder="Password" required >
                                    <input type="hidden" name="logintoken" value="<?php echo $logintoken; ?>" />
                                </div>
                            </div>
                            <button type="submit" onclick="handleLogin()" class="submitContactBtn w-100">Login <span>
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
                            <div class="rememberLostPassMain">
                                <div class="form-check customCheckbox">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                                    <label class="form-check-label" for="flexCheckChecked">
                                        Remember for 30 days
                                    </label>
                                </div>
                                <a href="<?php echo $CFG->wwwroot; ?>/login/forgot_password.php">Lost Password?</a>
                            </div>
                        </form>
                    </div>
                    <div class="SCorCreateBox">
                        <div class="SCorCreateBoxContent">
                            <h3>Is this your first time here?</h3>
                            <h4>Hi !</h4>
                            <p>For full access to courses you'll need to create yourself an account.</p>

                            <p> All you need to do is make up a username and password and use it in the form on this
                                page!</p>

                            <p> If someone else has already chosen your username then you'll have to try again using a
                                different username.</p>
                            <img src="/assets/images/dashboard/loginimg.png" alt="login image"
                                class="img-fluid mt-4 px-4">
                        </div>
                        <button type="button" onclick="window.location.href='<?php echo $CFG->wwwroot; ?>/login/signup.php'" class="SCorCreateNewAccount w-100">Create New Account</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
       <?php
    require_once('footer.php');
  ?>

    <script src="assets/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
        crossorigin="anonymous"></script>
</body>

</html>