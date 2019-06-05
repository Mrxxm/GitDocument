(function () {
    'use strict';

    const webSocketServer = require('ws').Server;
    const ws = new webSocketServer({
        port: 8282
    });
    ws.on('connection', function(ws) {
        console.log('客户端已经连接');
        // 接收到小游戏数据，所调用的方法
        ws.on('message', function (message) {
            console.log(message);
            // 向客户端返回数据
            ws.send('123');
        });
    })
})();