<?php
require('config.php'); // Adjust the path based on location
session_start();

$code = substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ23456789"), 0, 6);
$SESSION->captcha_code = $code;

$width = 150;
$height = 50;
$image = imagecreatetruecolor($width, $height);

$bg_color = imagecolorallocate($image, 255, 255, 255);
$text_color = imagecolorallocate($image, 0, 0, 0);
$noise_color = imagecolorallocate($image, 100, 120, 180);

imagefilledrectangle($image, 0, 0, $width, $height, $bg_color);

for ($i = 0; $i < 100; $i++) {
    imageellipse($image, rand(0, $width), rand(0, $height), 1, 1, $noise_color);
}

// Use built-in font if TTF not available
imagestring($image, 5, 30, 15, $code, $text_color);

// Output image
header('Content-Type: image/png');
imagepng($image);
imagedestroy($image);
?>
