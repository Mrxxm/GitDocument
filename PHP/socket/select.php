<?php
date_default_timezone_set('Asia/Shanghai');
$master = [];
$socket = stream_socket_server("tcp://0.0.0.0:8000", $errno, $errstr);

$master[] = $socket;

while (1) {
    $read = $master;
    $_w = $_e = NULL;
    $mod_fd = stream_select($read,$_w ,$_e, 5);

    if(!$mod_fd) continue;
    
    $fds = array_keys($read);
    foreach($fds as $i){
        
        if ($read[$i] === $socket) {
            $conn = stream_socket_accept($socket);
            $master[] = $conn;
        } else {

            $sock_data = fread($read[$i], 1024);
            if (strlen($sock_data) === 0) {
                $key_to_del = array_search($read[$i], $master, TRUE);
                fclose($read[$i]);
                unset($master[$key_to_del]);
            } else if ($sock_data === FALSE) {
                echo "Something bad happened";
                $key_to_del = array_search($read[$i], $master, TRUE);
                unset($master[$key_to_del]);
            } else {
                fwrite($conn, "Hello! The time is ".date("n/j/Y g:i a")."\n");
                fwrite($read[$i], "You have sent :[".$sock_data."]\n");
                fclose($read[$i]);
                $key_to_del = array_search($read[$i], $master);
                unset($master[$key_to_del]);
            }
        }
    }
}