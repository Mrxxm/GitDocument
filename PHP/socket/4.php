<?php
$fp = stream_socket_client("udp://127.0.0.1:1113",$errno,$errstr);

if(!$fp){
	echo "$errno,$errstr";
}else{
	fwrite($fp,"学PHP只上慕课网\n");
	echo fread($fp,1024);
	fclose($fp);
}

