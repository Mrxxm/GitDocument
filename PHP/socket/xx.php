<?php
$socket = stream_socket_server("tcp://127.0.0.1:8001",$errno,$errstr);

if(!$socket){
	echo "$errstr";
}else{
	for(;;){
		$client = stream_socket_accept($socket,-1);
		if($client){
			$http = fread($client,1024);

			$content = "HTTP/1.1 200 OK\r\nServer:http_imooc/1.0.0\r\nContent-Length:".strlen($http)."\r\n\r\n{$http}";

			fwrite($client, $content);
		}
		fclose($client);
	}
	fclose($socket);
}