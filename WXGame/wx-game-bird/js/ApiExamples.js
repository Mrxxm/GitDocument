export class ApiExamples {
    getUserInfo() {
        wx.getUserInfo({
            success: function (res) {
                console.log(res);
            }
        });
    }

    login() {
        wx.login({
            success: function (res) {
                console.log(res);
            }
        });
    }

    getSetting () {
        wx.getSetting({
            success: function (res) {
                console.log(JSON.stringify(res));
            }
        });
    }

    httpExample() {
        wx.request({
            url: 'http://127.0.0.1:8181',
            method: 'POST',
            data: 'MyData',
            success: function (response) {
                console.log(response);
            }
        });
    }

    socketExample() {
        wx.connectSocket({
            url: 'ws://127.0.0.1:8282',
            success: function () {
                console.log('客户端链接成功');
            }
        });

        wx.onSocketOpen(function () {
            // 向服务端发送数据
            wx.sendSocketMessage({
                data: '这个是客户端发来的数据'
            });
            // 监听服务器发送来的数据
            wx.onSocketMessage(function (message) {
                console.log(message);
            })
        });
    }

    download() {
        wx.downloadFile({
            url: 'https://img3.doubanio.com/view/photo/l/public/p2550465636.webp',
            success: function (tmp) {
                console.log(JSON.stringify(tmp))
            }
        });
    }

}