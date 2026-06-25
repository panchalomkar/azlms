<?php
require_once('config.php');
if(!empty($_POST)){

    $user_input = required_param('captcha_input', PARAM_TEXT); //trim($_POST['captcha_input'] ?? '');
    $user_input = trim($user_input);
    if (isset($SESSION->captcha_code) && strtolower($user_input) === strtolower($SESSION->captcha_code)) {
        echo "1";
    }else{
        echo "0";
    }
}else{
    echo "2";
}
?>
