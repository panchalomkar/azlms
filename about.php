<!DOCTYPE html>
<html lang="en">

<head>
  <?php 
  $page_title = "About Us";
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
    </div>
  </nav>

  <section class="heroSection aboutHeroSection">
    <div class="container position-relative">
      <div class="d-flex justify-content-between align-items-center">
        <div class="heroContent">
          <h1>About Us</h1>
        </div>
        <div>
          <svg width="180" height="180" viewBox="0 0 180 180" fill="none">
            <path
              d="M43.4758 14.0488C28.5896 14.0488 16.4788 26.4758 16.4788 41.7508C16.4788 57.0258 28.5896 69.4531 43.4758 69.4531C58.3623 69.4531 70.4731 57.0258 70.4731 41.7508C70.4731 26.4758 58.3623 14.0488 43.4758 14.0488ZM43.4819 80.525C19.5004 80.525 0 100.025 0 123.995V142.62H14.1942V129.8H25.6088V142.62H74.6715C75.3308 131.656 78.99 121.309 85.3769 112.368C80.2212 93.7715 62.9938 80.525 43.4819 80.525ZM132.989 28.5758C116.806 28.5758 103.641 41.9454 103.641 58.3788C103.641 74.8127 116.806 88.1823 132.989 88.1823C149.172 88.1823 162.337 74.8127 162.337 58.3788C162.337 41.9454 149.172 28.5758 132.989 28.5758ZM132.995 99.2323C107.064 99.2323 85.9777 120.319 85.9777 146.238V165.951H102.012V151.966H113.427V165.951H152.551V151.966H163.965V165.951H180V146.238C180 120.319 158.913 99.2323 132.995 99.2323Z"
              fill="white" />
          </svg>
        </div>
      </div>
    </div>
    <div class="bg-shape aboutHeroBg1"></div>
  </section>
  <section class="whyChooseUsSection sectionGap">
    <div class="container">
      <div class="allSecPageHeading">
        <h2>Why choose us?</h2>
      </div>
      <div class="row gy-3 gx-5">
        <div class="col-md-6 whyChooseUsMainCol">
          <div class="whyChooseUsContent">
            <svg width="140" height="140" viewBox="0 0 140 140" fill="none">
              <path
                d="M69.8633 46.6211C75.2092 46.6211 79.543 41.3569 79.543 34.8633C79.543 28.3696 75.2092 23.1055 69.8633 23.1055C64.5173 23.1055 60.1836 28.3696 60.1836 34.8633C60.1836 41.3569 64.5173 46.6211 69.8633 46.6211Z"
                fill="#B0C4EF" />
              <path
                d="M102.156 70.6289C101.828 69.9453 101.309 69.3438 100.816 68.7695C97.5077 64.9688 94.1718 61.1953 90.4257 57.75C88.5663 56.0273 86.2694 53.9219 83.8085 52.1992V52.1445C82.9062 51.4336 81.9491 50.7773 80.8827 50.2852C80.1718 49.9297 79.4335 49.6562 78.6679 49.4375C78.2577 49.3008 77.8202 49.2187 77.3827 49.1367C77.0819 50.2305 75.7148 55.8086 75.1132 58.3242C74.9491 59.0625 74.2929 59.5547 73.5272 59.5547H72.6796L71.8866 53.8125L72.4882 50.6953C72.5702 50.2852 72.4062 49.9023 72.078 49.6016L70.9843 48.6445C70.7108 48.3984 70.328 48.2891 69.9726 48.2891C69.6171 48.2891 69.2343 48.3984 68.9608 48.6445L67.8671 49.6016C67.539 49.875 67.4023 50.2852 67.4569 50.6953L68.0585 53.8125L67.2655 59.5547H66.4452C65.7069 59.5547 65.0507 59.0352 64.8593 58.3242C64.2577 55.8086 62.8905 50.2305 62.5897 49.1367C62.1522 49.2187 61.7421 49.3008 61.3046 49.4375C60.539 49.6562 59.8007 49.9297 59.0898 50.2852C58.0507 50.7773 57.0937 51.4336 56.164 52.1445V52.1992C53.7304 53.9219 51.4335 56.0547 49.5468 57.75C45.8007 61.168 42.4647 64.9688 39.1562 68.7695C38.6366 69.3438 38.1444 69.9453 37.8163 70.6289C37.0507 72.2422 37.4608 74.2109 38.4179 75.7422C39.3749 77.2734 40.7968 78.4766 42.1913 79.6523C47.3593 84 52.5272 88.3203 57.6952 92.668C58.4882 106.066 59.3085 119.438 60.1015 132.836C60.1835 134.094 60.2655 135.406 60.8398 136.527C61.414 137.648 62.6444 138.551 63.9023 138.332C65.1054 138.113 65.953 136.992 66.4179 135.871C67.0194 134.422 67.2929 132.836 67.5116 131.277C67.621 130.621 67.6757 129.992 67.7851 129.336L69.9726 93.3242L72.1601 129.336C72.2421 129.992 72.3241 130.621 72.4335 131.277C72.6796 132.836 72.9257 134.422 73.5272 135.871C73.9921 136.992 74.8398 138.141 76.0429 138.332C77.3007 138.551 78.5038 137.648 79.1054 136.527C79.6796 135.406 79.789 134.094 79.8437 132.836C80.6366 119.438 81.4569 106.066 82.2499 92.668C87.4179 88.3203 92.5858 84 97.7538 79.6523C99.1483 78.4766 100.57 77.2734 101.527 75.7422C102.512 74.2109 102.922 72.2695 102.156 70.6289ZM55.8905 80.5273C53.9491 78.9961 52.0077 77.4648 50.0663 75.9609C49.4374 75.4687 48.7538 74.8945 48.5624 74.1289C48.2616 72.9531 49.1913 71.8594 50.0663 70.9844C51.953 69.1797 54.0038 67.5391 56.164 66.0625V67.375C56.4374 72.1328 56.7382 76.8906 57.0116 81.6484C56.7382 81.2383 56.3007 80.8555 55.8905 80.5273ZM91.4374 74.1289C91.246 74.8945 90.5624 75.4414 89.9335 75.9609C87.9921 77.4922 86.0507 79.0234 84.1093 80.5273C83.6718 80.8555 83.2616 81.2383 82.9608 81.6758C83.2343 76.918 83.5351 72.1602 83.8085 67.4023V66.0898C85.9687 67.5664 88.0194 69.207 89.9062 71.0117C90.8085 71.8594 91.7382 72.9531 91.4374 74.1289Z"
                fill="#B0C4EF" />
              <path fill-rule="evenodd" clip-rule="evenodd"
                d="M39.8672 38.6367L43.2305 39.0469C44.0781 39.1562 44.7891 39.7578 45.0352 40.5781C45.582 42.5195 46.3477 44.3516 47.3047 46.0742C47.7148 46.8398 47.6602 47.7695 47.1133 48.4531L45.0352 51.1055C44.3789 51.9531 44.4336 53.1836 45.1992 53.9492L45.4453 54.1953C45.6094 54.0586 45.7461 53.8945 45.9102 53.7578L46.1562 53.5391C47.8516 51.9805 49.9297 50.0664 52.2266 48.3711L52.8555 47.8789C54.1953 46.8398 55.4805 46.0195 56.7656 45.418C56.957 45.3359 57.1758 45.2539 57.3672 45.1719C54.5234 42.1094 52.8008 38.0625 52.8008 33.5508C52.8008 28.8203 54.7148 24.5547 57.8047 21.4648C60.8945 18.375 65.1602 16.4609 69.8906 16.4609C74.6211 16.4609 78.8867 18.375 81.9766 21.4648C85.0391 24.5273 86.9531 28.793 86.9531 33.5234C86.9531 37.9805 85.2305 42 82.4414 45.0625C82.7148 45.1719 82.9883 45.2812 83.2344 45.418C84.5195 46.0195 85.8047 46.8398 87.1445 47.8789L89.2227 49.5195C90.9453 50.9141 92.5039 52.3359 93.8164 53.5391L94.0625 53.7578C94.1719 53.8672 94.2813 53.9766 94.3906 54.0586L94.5 53.9492C95.2656 53.1836 95.3477 51.9531 94.6641 51.1055L92.5859 48.4531C92.3296 48.1165 92.1753 47.7134 92.1414 47.2917C92.1075 46.87 92.1953 46.4474 92.3945 46.0742C93.3516 44.3516 94.1172 42.5195 94.6641 40.5781C94.9102 39.7578 95.6211 39.1289 96.4687 39.0469L99.832 38.6367C100.898 38.5 101.719 37.5977 101.719 36.5039V30.5156C101.719 29.4219 100.898 28.5195 99.832 28.3828L96.4687 27.9727C95.6211 27.8633 94.9102 27.2617 94.6641 26.4414C94.1172 24.5 93.3516 22.668 92.3945 20.9453C91.9844 20.1797 92.0391 19.25 92.5859 18.5664L94.6641 15.9141C95.3203 15.0664 95.2656 13.8359 94.5 13.0703L90.2617 8.83203C89.4961 8.06641 88.2656 7.98437 87.418 8.66797L84.7656 10.7461C84.429 11.0024 84.0259 11.1567 83.6042 11.1906C83.1825 11.2246 82.7599 11.1367 82.3867 10.9375C80.2812 9.76172 77.957 8.85937 75.5508 8.3125L74.9766 3.5C74.8672 2.46094 73.9375 1.64062 72.8437 1.64062H66.8555C65.7617 1.64062 64.8594 2.46094 64.7227 3.52734L64.3125 6.89062C64.2031 7.73828 63.6016 8.44922 62.7812 8.69531C60.8398 9.24219 59.0078 10.0078 57.2852 10.9648C56.5195 11.375 55.5898 11.3203 54.9062 10.7734L52.2539 8.69531C51.4063 8.03906 50.1758 8.09375 49.4102 8.85937L45.1719 13.0977C44.4062 13.8633 44.3242 15.0937 45.0078 15.9414L47.0859 18.5937C47.6055 19.2773 47.6875 20.207 47.2773 20.9727C46.3203 22.6953 45.5547 24.5273 45.0078 26.4687C44.7617 27.2891 44.0508 27.918 43.2031 28L39.8398 28.4102C38.7734 28.5469 37.9531 29.4492 37.9531 30.543V36.5312C37.9805 37.5977 38.8008 38.5273 39.8672 38.6367Z"
                fill="#4E5D8D" />
            </svg>
          </div>
          <div class="whyChooseUsText">
            <h3>A Wealth of Experience</h3>
            <p>Our team brings over 100 years of combined education and healthcare expertise</p>
          </div>
        </div>
        <div class="col-md-6 whyChooseUsMainCol">
          <div class="whyChooseUsContent">
            <svg width="98" height="98" viewBox="0 0 98 98" fill="none">
              <path
                d="M23.7605 68.8671C23.7605 70.2998 22.5947 71.4656 21.1621 71.4656C19.7294 71.4656 18.5636 70.2998 18.5636 68.8671V57.5379H15.5939V68.8671C15.5939 70.2998 14.428 71.4656 12.9954 71.4656C11.5627 71.4656 10.3969 70.2998 10.3969 68.8671V50.6777H23.7605V68.8671ZM40.8363 28.405H26.7302C25.0901 28.405 23.7605 29.7346 23.7605 31.3747V47.708H10.3969V44.1743V43.9959V31.1818H7.4272V43.9959C7.4272 45.2241 6.42812 46.2232 5.19993 46.2232C3.97174 46.2232 2.97266 45.2241 2.97266 43.9959V28.8364C2.97266 26.138 5.16016 23.9504 7.85859 23.9504H40.8363C42.0645 23.9504 43.0636 24.9495 43.0636 26.1777C43.0636 27.4059 42.0645 28.405 40.8363 28.405ZM17.0787 9.58966C20.3536 9.58966 23.0181 12.2541 23.0181 15.5291C23.0181 18.804 20.3536 21.4684 17.0787 21.4684C13.8038 21.4684 11.1393 18.804 11.1393 15.5291C11.1393 12.2541 13.8038 9.58966 17.0787 9.58966Z"
                fill="#B0C4EF" />
              <path
                d="M29.4563 2.22729V20.9809H40.8302C43.696 20.9809 46.0272 23.3122 46.0272 26.1779C46.0272 29.0437 43.696 31.3749 40.8302 31.3749H29.4563V43.2537H95.0272V2.22729H29.4563ZM81.1141 40.0018H43.3693V37.0321H81.1141V40.0018ZM81.1141 30.9739H50.7936V28.0042H81.1141V30.9739ZM81.1141 21.9312H50.7936V18.9615H81.1141V21.9312ZM81.1141 12.9034H43.3693V9.93366H81.1141V12.9034ZM93.5423 88.5712V95.7728H61.6627V88.586C61.6627 85.9133 63.3406 83.5376 65.8648 82.6467L72.3685 80.36C75.4007 81.9282 80.237 81.6898 82.8366 80.3452L89.3551 82.6467C91.8645 83.5376 93.5423 85.9133 93.5423 88.5712Z"
                fill="#4E5D8D" />
              <path
                d="M85.36 66.6924V71.508C85.36 80.7655 69.8484 80.906 69.8484 71.508V66.6924C69.8484 63.224 72.66 60.4124 76.1284 60.4124H79.08C82.5484 60.4124 85.36 63.224 85.36 66.6924ZM58.6931 88.5712V95.7727H26.7986V88.586C26.7986 85.9133 28.4912 83.5376 31.0006 82.6466L37.5043 80.3749C40.7313 81.932 45.5255 81.6254 47.9724 80.36L54.491 82.6466C57.0152 83.5376 58.6931 85.9133 58.6931 88.5712Z"
                fill="#4E5D8D" />
              <path
                d="M50.502 66.6924V71.5079C50.502 80.7655 34.9903 80.906 34.9903 71.5079V66.6924C34.9903 63.224 37.802 60.4123 41.2703 60.4123H44.2219C47.6903 60.4123 50.502 63.224 50.502 66.6924ZM95.0272 46.2233V49.1336C95.0272 52.1924 92.5326 54.6869 89.459 54.6869H35.0245C31.9508 54.6869 29.4563 52.1924 29.4563 49.1336V46.2233H95.0272Z"
                fill="#4E5D8D" />
            </svg>
          </div>
          <div class="whyChooseUsText">
            <h3>Choose the Learning Path That Works for You</h3>
            <p>Skills- based training for real- world success Get the best of both worlds! Study online and visit </p>
          </div>
        </div>
        <div class="col-md-6 whyChooseUsMainCol">
          <div class="whyChooseUsContent">
            <svg width="86" height="86" viewBox="0 0 86 86" fill="none">
              <path
                d="M67.0027 8.53284C57.0758 8.53284 49.0133 16.5785 49.0133 26.5223C49.0133 32.2164 51.6672 37.2891 55.7824 40.5813C54.1699 42.8489 51.9359 45.3012 48.9629 46.9641C48.9629 46.9641 54.4891 47.6024 61.0734 43.5039C62.9211 44.159 64.9199 44.5117 67.0027 44.5117C76.9297 44.5117 84.9922 36.466 84.9922 26.5223C84.9922 16.5785 76.9465 8.53284 67.0027 8.53284ZM57.9324 30.3016C56.2359 30.3016 54.8586 28.9242 54.8586 27.2278C54.8586 25.5313 56.2359 24.1539 57.9324 24.1539C59.6289 24.1539 61.0063 25.5313 61.0063 27.2278C61.0063 28.9242 59.6289 30.3016 57.9324 30.3016ZM66.9691 30.3016C65.2727 30.3016 63.8953 28.9242 63.8953 27.2278C63.8953 25.5313 65.2727 24.1539 66.9691 24.1539C68.6656 24.1539 70.043 25.5313 70.043 27.2278C70.0598 28.9242 68.6824 30.3016 66.9691 30.3016ZM76.0227 30.3016C74.3262 30.3016 72.9488 28.9242 72.9488 27.2278C72.9488 25.5313 74.3262 24.1539 76.0227 24.1539C77.7191 24.1539 79.0965 25.5313 79.0965 27.2278C79.0965 28.9242 77.7191 30.3016 76.0227 30.3016ZM55.2785 60.8215C55.2785 67.1203 60.3848 72.2266 66.6836 72.2266C67.9937 72.2266 69.2703 72.0082 70.4461 71.5883C74.6285 74.175 78.1223 73.7719 78.1223 73.7719C76.241 72.7137 74.8133 71.1684 73.7887 69.7239C76.409 67.641 78.0887 64.416 78.0887 60.8047C78.0887 54.5059 72.9824 49.3996 66.6836 49.3996C60.3848 49.4164 55.2785 54.5227 55.2785 60.8215ZM70.4965 61.2582C70.4965 60.1832 71.3699 59.3098 72.4449 59.3098C73.5199 59.3098 74.3934 60.1832 74.3934 61.2582C74.3934 62.3332 73.5199 63.2067 72.4449 63.2067C71.3699 63.2067 70.4965 62.3332 70.4965 61.2582ZM64.752 61.2582C64.752 60.1832 65.6254 59.3098 66.7004 59.3098C67.7754 59.3098 68.6488 60.1832 68.6488 61.2582C68.6488 62.3332 67.7754 63.2067 66.7004 63.2067C65.6254 63.2067 64.752 62.3332 64.752 61.2582ZM59.0242 61.2582C59.0242 60.1832 59.8977 59.3098 60.9727 59.3098C62.0477 59.3098 62.9211 60.1832 62.9211 61.2582C62.9211 62.3332 62.0477 63.2067 60.9727 63.2067C59.8977 63.2067 59.0242 62.3332 59.0242 61.2582Z"
                fill="#4E5D8D" />
              <path fill-rule="evenodd" clip-rule="evenodd"
                d="M26.6566 47.4511C30.9566 47.4511 34.887 45.889 37.9273 43.3191L47.7702 42.8152L43.7222 25.9176L43.571 25.7496C41.673 18.1742 34.8367 12.5472 26.6566 12.5472C18.4765 12.5472 11.6402 18.1574 9.74212 25.7496L9.59095 25.9176L5.5597 42.8152L15.4027 43.3191C18.4429 45.889 22.3566 47.4511 26.6566 47.4511ZM48.7781 54.6738C45.0659 52.7254 38.7167 48.6773 38.7167 48.6773L43.3023 58.7386L36.7683 59.3769L26.6566 72.5121L16.5449 59.3769L10.0109 58.7386L14.6132 48.6773C14.6132 48.6773 8.264 52.7422 4.55189 54.6738C0.83978 56.6222 1.00775 62.5012 1.00775 62.5012V77.4672H52.3054V62.5012C52.3054 62.5012 52.4902 56.6222 48.7781 54.6738Z"
                fill="#B0C4EF" />
              <path fill-rule="evenodd" clip-rule="evenodd"
                d="M34.1144 49.4836C31.746 50.3907 29.2265 50.8778 26.6565 50.8778C24.0866 50.8778 21.5671 50.3907 19.1987 49.4836V50.6258L26.6565 72.529L34.1144 50.6258V49.4836Z"
                fill="#B0C4EF" />
            </svg>
          </div>
          <div class="whyChooseUsText">
            <h3>Job Placement Support</h3>
            <p>Skills- based training for real world success</p>
          </div>
        </div>
        <div class="col-md-6 whyChooseUsMainCol">
          <div class="whyChooseUsContent">
            <svg width="78" height="78" viewBox="0 0 78 78" fill="none">
              <g clip-path="url(#clip0_1_401)">
                <path
                  d="M74.1488 11.5781H64.6669V17.4525H72.1256V50.0297H5.87438V17.4525H13.3331V11.5781H3.83906C2.81976 11.5814 1.84331 11.9885 1.12369 12.7104C0.404077 13.4323 -5.10385e-06 14.4101 0 15.4294L0 61.5103C0.00321391 62.5275 0.408717 63.5021 1.12799 64.2214C1.84725 64.9407 2.82187 65.3462 3.83906 65.3494H29.5547L30.3713 69.4688H27.495C26.6769 69.4718 25.8924 69.7948 25.3093 70.3688C24.7263 70.9427 24.3909 71.722 24.375 72.54V77.9269H53.625V72.54C53.6091 71.722 53.2737 70.9427 52.6907 70.3688C52.1076 69.7948 51.3231 69.4718 50.505 69.4688H47.6897L48.5184 65.4225H74.1488C74.6597 65.4226 75.1655 65.321 75.6368 65.1236C76.108 64.9263 76.5353 64.6371 76.8938 64.273C77.2522 63.9089 77.5346 63.4772 77.7245 63.0028C77.9144 62.5285 78.0081 62.0212 78 61.5103V15.4294C77.9968 14.4089 77.59 13.4312 76.8684 12.7097C76.1469 11.9881 75.1692 11.5813 74.1488 11.5781ZM42.6562 58.0003H35.1853C35.0274 58.0168 34.8678 57.9998 34.7169 57.9507C34.566 57.9015 34.427 57.8212 34.3091 57.7149C34.1912 57.6086 34.0969 57.4788 34.0324 57.3337C33.9678 57.1887 33.9345 57.0317 33.9345 56.873C33.9345 56.7142 33.9678 56.5572 34.0324 56.4122C34.0969 56.2672 34.1912 56.1373 34.3091 56.031C34.427 55.9248 34.566 55.8444 34.7169 55.7953C34.8678 55.7461 35.0274 55.7292 35.1853 55.7456H42.6562C42.8141 55.7292 42.9737 55.7461 43.1247 55.7953C43.2756 55.8444 43.4145 55.9248 43.5325 56.031C43.6504 56.1373 43.7446 56.2672 43.8092 56.4122C43.8737 56.5572 43.9071 56.7142 43.9071 56.873C43.9071 57.0317 43.8737 57.1887 43.8092 57.3337C43.7446 57.4788 43.6504 57.6086 43.5325 57.7149C43.4145 57.8212 43.2756 57.9015 43.1247 57.9507C42.9737 57.9998 42.8141 58.0168 42.6562 58.0003Z"
                  fill="#4E5D8D" />
                <path
                  d="M21.645 32.8209C27.1112 32.9561 32.4963 34.1739 37.4888 36.404L38.5247 36.855V4.74093L38.2078 4.52155C33.5189 1.47435 28.0151 -0.0769239 22.425 0.0731128C19.8942 0.0726094 17.3701 0.334001 14.8931 0.853113L14.3203 0.987175V33.8934L15.2344 33.6497C17.3278 33.1042 19.4817 32.8258 21.645 32.8209ZM17.7084 9.26249C17.793 9.1665 17.9103 9.10563 18.0375 9.09186C19.3902 8.95966 20.749 8.89863 22.1081 8.90905C26.2292 8.85914 30.3353 9.41347 34.2956 10.5544C34.3588 10.5724 34.4177 10.603 34.4687 10.6444C34.5197 10.6857 34.5619 10.737 34.5926 10.795C34.6233 10.8531 34.642 10.9167 34.6475 10.9822C34.653 11.0477 34.6453 11.1135 34.6247 11.1759C34.5898 11.2746 34.5256 11.3601 34.4406 11.4212C34.3556 11.4822 34.254 11.5157 34.1494 11.5172H33.9909C30.117 10.4073 26.1009 9.87351 22.0716 9.9328C19.6341 9.9328 18.1228 10.0912 18.0863 10.0912C17.9644 10.0882 17.8484 10.0384 17.7622 9.95217C17.676 9.86598 17.6262 9.74997 17.6231 9.62811C17.6076 9.56476 17.6072 9.49862 17.622 9.43508C17.6368 9.37155 17.6664 9.31241 17.7084 9.26249ZM17.7084 16.185C17.7914 16.087 17.9096 16.0257 18.0375 16.0144C18.1106 16.0144 19.7316 15.8437 22.1081 15.8437C26.2286 15.789 30.3349 16.3392 34.2956 17.4769C34.3592 17.4963 34.4183 17.5282 34.4695 17.5707C34.5206 17.6132 34.5627 17.6655 34.5934 17.7246C34.624 17.7836 34.6426 17.8481 34.648 17.9144C34.6533 17.9807 34.6454 18.0474 34.6247 18.1106C34.5898 18.2092 34.5256 18.2948 34.4406 18.3558C34.3556 18.4169 34.254 18.4504 34.1494 18.4519H33.9909C30.1187 17.3327 26.1018 16.7946 22.0716 16.8553C19.8534 16.8553 18.2569 16.9894 18.0863 17.0137C17.9664 17.0108 17.8521 16.9627 17.7662 16.8791C17.6804 16.7955 17.6292 16.6825 17.6231 16.5628C17.6084 16.4974 17.6084 16.4296 17.6232 16.3643C17.6379 16.2989 17.667 16.2377 17.7084 16.185ZM17.7084 23.1075C17.7914 23.0095 17.9096 22.9482 18.0375 22.9369C18.2081 22.9369 19.8047 22.7662 22.1447 22.7662C26.2663 22.7067 30.3739 23.2612 34.3322 24.4116C34.3953 24.4296 34.4542 24.4602 34.5052 24.5016C34.5563 24.5429 34.5984 24.5942 34.6292 24.6522C34.6599 24.7103 34.6786 24.7739 34.6841 24.8394C34.6896 24.9048 34.6818 24.9707 34.6613 25.0331C34.6259 25.1305 34.5612 25.2144 34.476 25.2734C34.3909 25.3323 34.2895 25.3633 34.1859 25.3622H34.0275C30.154 24.2491 26.138 23.7112 22.1081 23.7656C19.6706 23.7656 18.1594 23.924 18.1228 23.924C18.003 23.9211 17.8887 23.873 17.8028 23.7894C17.7169 23.7058 17.6658 23.5928 17.6597 23.4731C17.6366 23.3651 17.6539 23.2523 17.7084 23.1562V23.1075ZM56.355 32.8209C58.5166 32.8154 60.6704 33.0815 62.7656 33.6131L63.6797 33.8569V0.987175L63.1069 0.853113C60.6367 0.344727 58.1214 0.0874806 55.5994 0.0853003C49.9886 -0.0779568 44.4615 1.47389 39.7556 4.53374L39.4388 4.75311V36.855L40.4625 36.404C45.4707 34.1698 50.8726 32.9519 56.355 32.8209ZM43.7166 10.5544C47.6769 9.41347 51.783 8.85914 55.9041 8.90905C57.2592 8.8979 58.6139 8.95893 59.9625 9.09186C60.0279 9.0959 60.0919 9.11309 60.1505 9.1424C60.2091 9.17172 60.2613 9.21256 60.3037 9.26249C60.3463 9.31229 60.3771 9.37098 60.3939 9.43425C60.4108 9.49752 60.4133 9.56376 60.4013 9.62811C60.382 9.7458 60.3233 9.85341 60.2346 9.93318C60.146 10.0129 60.0328 10.0601 59.9137 10.0669C58.597 9.94605 57.275 9.89316 55.9528 9.90843C51.9244 9.85026 47.909 10.3799 44.0334 11.4806C43.9813 11.4922 43.9272 11.4922 43.875 11.4806C43.7704 11.4791 43.6688 11.4456 43.5838 11.3846C43.4988 11.3236 43.4345 11.238 43.3997 11.1394C43.3686 11.0199 43.384 10.893 43.4428 10.7844C43.5016 10.6758 43.5995 10.5936 43.7166 10.5544ZM43.7166 17.4769C47.6773 16.3392 51.7835 15.789 55.9041 15.8437C58.3416 15.8437 59.9016 16.0022 59.9869 16.0144C60.1104 16.0287 60.2238 16.0898 60.3037 16.185C60.3448 16.2356 60.3747 16.2944 60.3915 16.3574C60.4083 16.4204 60.4117 16.4862 60.4013 16.5506C60.3839 16.6713 60.324 16.7817 60.2324 16.8622C60.1408 16.9426 60.0235 16.9877 59.9016 16.9894C59.7675 16.9894 58.1587 16.8309 55.9528 16.8309C51.9226 16.7702 47.9056 17.3083 44.0334 18.4275H43.875C43.7704 18.426 43.6688 18.3925 43.5838 18.3315C43.4988 18.2705 43.4345 18.1849 43.3997 18.0862C43.3646 17.9634 43.3779 17.8318 43.4368 17.7185C43.4958 17.6051 43.5959 17.5187 43.7166 17.4769ZM43.7166 24.4116C47.6749 23.2612 51.7824 22.7067 55.9041 22.7662C58.2441 22.7662 59.8406 22.9125 60.0234 22.9369C60.147 22.9512 60.2603 23.0123 60.3403 23.1075C60.3814 23.1581 60.4113 23.2169 60.4281 23.2799C60.4449 23.3429 60.4482 23.4087 60.4378 23.4731C60.4186 23.5908 60.3598 23.6984 60.2712 23.7782C60.1825 23.8579 60.0694 23.9051 59.9503 23.9119C58.6336 23.791 57.3115 23.7382 55.9894 23.7534C51.9595 23.699 47.9435 24.2369 44.07 25.35H43.875C43.7714 25.3512 43.6701 25.3201 43.5849 25.2612C43.4998 25.2022 43.435 25.1183 43.3997 25.0209C43.375 24.9579 43.3636 24.8905 43.3664 24.8228C43.3692 24.7552 43.386 24.6889 43.4158 24.6282C43.4456 24.5674 43.4878 24.5135 43.5395 24.4699C43.5913 24.4263 43.6516 24.394 43.7166 24.375V24.4116Z"
                  fill="#B0C4EF" />
              </g>
              <defs>
                <clipPath id="clip0_1_401">
                  <rect width="78" height="78" fill="white" />
                </clipPath>
              </defs>
            </svg>
          </div>
          <div class="whyChooseUsText">
            <h3>Hybrid Learning</h3>
            <p>Get the best of both worlds! Study online and visit campus just one day per week</p>
          </div>
        </div>
        <div class="col-md-6 whyChooseUsMainCol">
          <div class="whyChooseUsContent">
            <svg width="80" height="80" viewBox="0 0 80 80" fill="none">
              <path
                d="M59.6251 34.7812C58.5626 30.9219 55.1094 28.25 51.1563 28.25H45.4688V24.2812C49.5313 22.2656 52.3282 18.0938 52.3282 13.2656C52.3126 6.45313 46.7969 0.9375 40.0001 0.9375C33.2032 0.9375 27.6876 6.45313 27.6876 13.25C27.6876 18.0938 30.4844 22.2656 34.5469 24.2656V28.2344H28.8594C24.9063 28.2344 21.4376 30.9062 20.3907 34.7656L16.6407 48.4531C16.4219 49.2812 17.1094 50.0625 18.0469 50.0625H36.3751L37.9376 38.1094L37.2657 34.4219C37.1876 33.9688 37.3438 33.5156 37.6876 33.2031L38.9532 32.0312C39.5313 31.5 40.4532 31.5 41.0157 32.0312L42.2813 33.2031C42.6251 33.5156 42.7813 33.9688 42.7032 34.4219L42.0313 38.1094L43.5938 50.0625H61.9219C62.8594 50.0625 63.5626 49.2656 63.3282 48.4531L59.6251 34.7812Z"
                fill="#B0C4EF" />
              <path
                d="M66.625 17.1875H55.125C54.5781 19.3125 53.5937 21.2812 52.2344 22.9688H69.3594C69.4531 23.25 69.5156 23.5625 69.5156 23.875V58.9062H10.4844V23.8906C10.4844 23.5625 10.5469 23.2656 10.6406 22.9844H27.7656C26.4062 21.2813 25.4219 19.3281 24.875 17.2031H13.375C9.6875 17.2031 6.67188 20.2031 6.67188 23.9062V61.7969C6.67188 65.4844 9.67187 68.5 13.375 68.5H31.6563V73.5H24.7813C23.2344 73.5 22 74.75 22 76.2812C22 77.8281 23.25 79.0625 24.7813 79.0625H55.2031C56.75 79.0625 57.9844 77.8125 57.9844 76.2812C57.9844 74.7344 56.7344 73.5 55.2031 73.5H48.3281V68.5H66.6094C70.2969 68.5 73.3125 65.5 73.3125 61.7969V23.8906C73.3125 20.2031 70.3125 17.1875 66.625 17.1875Z"
                fill="#4E5D8D" />
            </svg>
          </div>
          <div class="whyChooseUsText">
            <h3>100% Online Learning</h3>
            <p>training and face-to-face guidance from our expert.</p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="whyChooseUsSection sectionGap">
    <div class="container">
      <div class="allSecPageHeading">
        <h2>Our Team</h2>
      </div>
      <div class="RachelWhitesideSec">
        <div class="row g-4">
          <div class="col-md-3">
            <img src="/assets/images/about/rachel.png" alt="rachel whiteside" class="img-fluid">
          </div>
          <div class="col-md-9">
            <div class="rachelWhiteContent">
              <div class="RachelWhiteHead">
                <h3>Rachel Whiteside</h3>
                <p>Medical Assistant Instructor and Program Coordinator </p>
              </div>
              <p>Rachel Whiteside brings over 25 years of experience in the medical field and has spent the last 6
                years passionately serving as both a medical instructor and student services coordinator. Her
                dedication to student success goes beyond the classroom — she advocates for learners every step of the
                way, helping them build confidence, career skills, and a clear path forward. Rachel is deeply
                committed to uplifting her community through education and mentorship.</p>
              <h6>"My goal is to not only teach but to inspire. I believe every student has the potential to succeed
                when they’re supported, seen, and empowered.”</h6>
            </div>
          </div>
        </div>
      </div>
      <div class="row teamMemberBoxColGap">
        <div class="col-md-4">
          <div class="teamMemberBox">
            <div class="teamMemberImage">
              <img src="assets/images/about/team1.png" alt="Team Member" class="img-fluid" />
            </div>
            <div class="teamMemberInfo">
              <h4>Timothy Cozatt</h4>
              <p>Program Director</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="teamMemberBox">
            <div class="teamMemberImage">
              <img src="assets/images/about/team2.png" alt="Team Member" class="img-fluid" />
            </div>
            <div class="teamMemberInfo">
              <h4>Admissions Advisor</h4>
              <p>Admissions Advisor</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="teamMemberBox">
            <div class="teamMemberImage">
              <img src="assets/images/about/team3.png" alt="Team Member" class="img-fluid" />
            </div>
            <div class="teamMemberInfo">
              <h4>Tanvi Sachar</h4>
              <p>Chief of Staff</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="faqSection sectionGap">
    <div class="container">
      <div class="allSecPageHeading faqHeading">
        <h2>FAQ</h2>
      </div>
      <div class="faqContent">
        <div class="accordion customAccordion" id="faqAccordion">
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                aria-expanded="true" aria-controls="collapseOne">
                How do I Apply?
              </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
              data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                To apply for the Medical Assistant program, you can fill out the online application form on our website
                or contact our admissions office for assistance.
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                What is the application deadline?
              </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
              data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                The application deadline varies by program start date. We recommend applying at least 4-6 weeks before
                the desired start date to ensure all paperwork is processed in time.
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                Are there any prerequisite courses?
              </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
              data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                No specific prerequisites are required, but a high school diploma or GED is recommended. Basic computer
                skills and a passion for healthcare will be beneficial.
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingFour">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                Is Financial Options available?
              </button>
            </h2>
            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
              data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Yes, financial options  is available for those who qualify. Please contact our admissions office for more
                information on the application process.
              </div>
            </div>
          </div>
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

function isValidEmail(email) {
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
 // alert(regex.test(email));
  return regex.test(email);
}

const email = 'asahasgahsfsg.com';
if (isValidEmail(email)) {
  console.log('Valid email');
} else {
  console.log('Invalid email');
}
</script>
</body>

</html>