<?php
$conn = stream_socket_client("tcp://0.0.0.0:8000",$errno,$errstr,1);

if(!$conn){
	echo "$errstr($errno)<br/>";
}else{
	stream_socket_sendto($conn,"学PHP我只上慕课网\n");
	echo stream_get_contents($conn);
	fclose($conn);
}

