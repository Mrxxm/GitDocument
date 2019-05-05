<?php
$fp = fsockopen("127.0.0.1",8001,$errno,$errstr,1);

if(!$fp){
	echo($errstr);
}else{
	$out = "GET / HTTP/1.1\r\n";
	$out .= "HOST:127.0.0.1:8001\r\n";
	fwrite($fp, $out);

	while(!feof($fp)){
		echo fread($fp, 512);
	}
	fclose($fp);
}