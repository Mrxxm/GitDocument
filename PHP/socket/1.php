<?php
$socket = stream_socket_server("tcp://0.0.0.0:8000",$errno,$errstr);

if(!$socket){
	echo "$errstr($errno)<br/>";
}else{

	for(;;){
		$client = stream_socket_accept($socket,-1);

		if($client){
			$data = fread($client,1024);
			fwrite($client, $data);
		}
		fclose($client);
	}
	fclose($socket);
}