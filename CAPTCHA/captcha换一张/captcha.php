<?php

session_start();

// 资源型数据
$image   = imagecreatetruecolor(100, 30);
// 为一幅图像分配颜色
$bgcolor = imagecolorallocate($image, 255, 255, 255); 
// 区域填充
imagefill($image, 0, 0, $bgcolor);

$captcha_code = "";

// 填写随机字母和数字
for($i = 0; $i < 4; $i++) {

	$fontsize    = 6;
	$fontcolor   = imagecolorallocate($image, rand(0, 120), rand(0, 120), rand(0, 120));

	// 内容从字典中随机取出
	$data        = 'abcdefghigklmnopqrstuvwxyz1234567890';
	$fontcontent = substr($data, rand(0, strlen($data) - 1), 1);

	// 拼接验证码
	$captcha_code .= $fontcontent;
	
	$x = 19 + $i * 19;
	$y = rand(5, 10);

	// 填写数字
	imagestring($image, $fontsize, $x, $y, $fontcontent, $fontcolor);
}

$_SESSION['authcode'] = $captcha_code;

// 添加点
for($i = 0; $i < 200; $i++) {

	$pointcolor   = imagecolorallocate($image, rand(50, 200), rand(50, 200), rand(50, 200));
	imagesetpixel($image, rand(1, 99), rand(1, 29), $pointcolor);
}

// 添加线
for($i = 0; $i < 3; $i++) {
	$linecolor   = imagecolorallocate($image, rand(80, 220), rand(80, 220), rand(80, 220));
	imageline($image, rand(1, 99), rand(1, 29), rand(1, 99), rand(1, 29), $linecolor);
}


header('content-type: image/png');

imagepng($image);

imagedestroy($image);