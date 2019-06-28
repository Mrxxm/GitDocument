<?php

session_start();

// 资源型数据
$image   = imagecreatetruecolor(200, 60);
// 为一幅图像分配颜色
$bgcolor = imagecolorallocate($image, 255, 255, 255); 
// 区域填充
imagefill($image, 0, 0, $bgcolor);

$fontface = 'z.ttc';

// 汉子字典
$str = '卡恩嘎嘣高科技安慰你噶可能的观点看就按噶几看噶几看不惯就卡机款接口的那可就噶驾考宝典个就阿卡框架的轧空伽伽即可构建'; 

$strdb = str_split($str, 3); // 汉子切割每三个字节一个汉子

$captcha_code = "";

// 填写随机字母和数字
for($i = 0; $i < 4; $i++) {

	$fontsize    = 20;
	$fontcolor   = imagecolorallocate($image, rand(0, 120), rand(0, 120), rand(0, 120));

	$index = rand(0, count($strdb) - 1);
	$cn = $strdb[$index];

	// 拼接验证码
	$captcha_code .= $cn;
	
	$x = 36 + $i * 36;
	$y = rand(25, 35);

	// 填写汉子
	imagettftext($image, $fontsize, mt_rand(-60, 60), $x, $y, $fontcolor, $fontface, $cn);
}

$_SESSION['authcode'] = $captcha_code;

// 添加点
for($i = 0; $i < 200; $i++) {

	$pointcolor   = imagecolorallocate($image, rand(50, 200), rand(50, 200), rand(50, 200));
	imagesetpixel($image, rand(1, 199), rand(1, 59), $pointcolor);
}

// 添加线
for($i = 0; $i < 3; $i++) {
	$linecolor   = imagecolorallocate($image, rand(80, 220), rand(80, 220), rand(80, 220));
	imageline($image, rand(1, 199), rand(1, 59), rand(1, 199), rand(1, 59), $linecolor);
}


header('content-type: image/png');

imagepng($image);

imagedestroy($image);