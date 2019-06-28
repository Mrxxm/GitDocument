<?php

session_start();

$table = array(
	'1' => '1688',
	'2' => '阿里云',
	'3' => '百度'
);

$index = rand(1, count($table));

$value = $table[$index];

$_SESSION['authcode'] = $value;


$filename = dirname(__FILE__) . '/' . $index . '.jpg';
$contents = file_get_contents($filename); 


header('content-type: image/jpg');

echo $contents;

