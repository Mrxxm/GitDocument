<?php
date_default_timezone_set('PRC');

$serv = new swoole_websocket_server("127.0.0.1", 9502);

$serv->on('Open', function($server, $req) {
    echo "connection open: ".$req->fd;
    var_dump(apcu_store((string)$req->fd,rand(1,3)));

});

$serv->on('Message', function($server, $frame) {

    $start_fd = 0;
    while(true)
    {
        $conn_list = $server->connection_list($start_fd, 10);
        if($conn_list===false || count($conn_list) === 0)
        {
            echo "finish\n";
            break;
        }
        $start_fd = end($conn_list);

        foreach($conn_list as $fd)
        {
            $msg = json_decode($frame->data);

            $server->push($fd, json_encode([
                'username' => $msg->username,
                'message' => $msg->message,
                'now' => date("Y-m-d i:h:s",time()),
                'avatar' => apc_fetch((string)$frame->fd)
            ]));
        }
    }

});

$serv->on('Close', function($server, $fd) {
    echo "connection close: $fd\n";
});

$serv->start();