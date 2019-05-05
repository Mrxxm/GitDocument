#### TCP和UDP协议

对于CS模式来说：服务端会监听一个端口，客户端通过这个端口进行链接和通讯

* Mysql客户端->监听3306->Mysql

* 浏览器->监听80->Nginx

socket(套接字)

传输层协议：TCP、UDP

* TCP协议：可靠的、顺序的、面向连接的
* UDP协议：不可靠的、独立的、无序的、无连接的

#### tcp通讯函数流程之服务端连接

php提供了两种套接字编程的方法

1.Pecl扩展sockets

2.内置的`Stream Functions`

**TCP通讯函数流程**

服务端

* `stream_socket_server` 指定监听的函数和端口

* `stream_socket_accept` 阻塞、等待客户端的链接

* `fread、fwrite` 发送和接收客户端的数据

* `fclose`

客户端

* `stream_socket_client` 指定链接服务器的ip和端口

* `stream_socket_sendto` `stream_get_contents` 发送和接收服务端的数据

* `fclose`

![](https://img1.doubanio.com/view/photo/l/public/p2555459608.jpg)

服务端 `/1.php` 

```
<?php
# 指定服务端绑定的IP和端口
# 0.0.0.0代表本机所有IP
# 后两个参数是错误号和错误信息
$socket = stream_socket_server("tcp://0.0.0.0:8000",$errno,$errstr); 

if(!$socket){
	echo "$errstr($errno)<br/>";
}else{

    # 死循环
	for(;;){
        # 等待客户端的链接
        # 客户端链接未取得将会阻塞当前进程
        # 第二个参数时阻塞的超时时间
		$client = stream_socket_accept($socket,-1);

		if($client){
            # 读取客户端1024字节的数据
			$data = fread($client,1024);
            # 回写给客户端
			fwrite($client, $data);
		}
		fclose($client);
	}
	fclose($socket);
}
```

#### tcp通讯函数流程之客户端连接

客户端`/2.php`

```
<?php
# 指定链接服务端的IP和端口
$conn = stream_socket_client("tcp://0.0.0.0:8000",$errno,$errstr,1);

if(!$conn){
	echo "$errstr($errno)<br/>";
}else{
	stream_socket_sendto($conn,"学PHP我只上慕课网\n");
	echo stream_get_contents($conn);
	fclose($conn);
}
```

#### UDP通讯函数流程

* `stream_socket_recvfrom` 等待客户端发送的信息

![](https://img3.doubanio.com/view/photo/l/public/p2555464045.jpg)

服务端`/3.php` 

```
<?php
# 指定IP和端口
$socket = stream_socket_server("udp://127.0.0.1:1113",$errno,$errstr,STREAM_SERVER_BIND);

if(!$socket){
	dir("$errstr($errno)");
}

do{
    # 第二个参数是接收字节数
    # 第三个参数填0
    # 第四个参数为客户端的IP和端口号，方法默认赋值
	$data = stream_socket_recvfrom($socket,1024,0,$peer);

	stream_socket_sendto($socket,$data,0,$peer);

}while ($data !== false);
```

客户端`/4.php`

```
<?php
$fp = stream_socket_client("udp://127.0.0.1:1113",$errno,$errstr);

if(!$fp){
	echo "$errno,$errstr";
}else{
	fwrite($fp,"学PHP只上慕课网\n");
	echo fread($fp,1024);
	fclose($fp);
}
```

#### 使用socket搭建简易http服务

服务端`/xx.php`

```
<?php
$socket = stream_socket_server("tcp://127.0.0.1:8001",$errno,$errstr);

if(!$socket){
	echo "$errstr";
}else{
	for(;;){
		$client = stream_socket_accept($socket,-1);
		if($client){
			$http = fread($client,1024);

           # 内容主要是请求头部和请求正文
			$content = "HTTP/1.1 200 OK\r\nServer:http_imooc/1.0.0\r\nContent-Length:".strlen($http)."\r\n\r\n{$http}";

			fwrite($client, $content);
		}
		fclose($client);
	}
	fclose($socket);
}
```

运行服务端，然后打开浏览器访问`http://127.0.0.1:8001`

#### fsockopen函数

前面使用浏览器作为客户端访问http服务端

php函数`fsockopen`可以发起一个TCP、UDP请求

自写客户端去访问http服务端

```
<?php
# 最后一个参数传入超时时间
$fp = fsockopen("127.0.0.1",8001,$errno,$errstr,1);

if(!$fp){
	echo($errstr);
}else{
	$out = "GET / HTTP/1.1\r\n";
	$out .= "HOST:127.0.0.1:8001\r\n";
	fwrite($fp, $out);

    # 循环读句柄
	while(!feof($fp)){
		echo fread($fp, 512);
	}
	fclose($fp);
}
```

#### 多客户端请求受阻

服务端

```
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
```

客户端1

```
<?php
$conn = stream_socket_client("tcp://0.0.0.0:8000",$errno,$errstr,1);

if(!$conn){
	echo "$errstr($errno)<br/>";
}else{
    # 客户端1模拟阻塞10秒
    sleep(10);
	stream_socket_sendto($conn,"学PHP我只上慕课网\n");
	echo stream_get_contents($conn);
	fclose($conn);
}

```

客户端2

```
<?php
$conn = stream_socket_client("tcp://0.0.0.0:8000",$errno,$errstr,1);

if(!$conn){
	echo "$errstr($errno)<br/>";
}else{
	stream_socket_sendto($conn,"学PHP我只上慕课网\n");
	echo stream_get_contents($conn);
	fclose($conn);
}

```

操作：运行服务端，再运行客户端1的同时运行客户端2。则需要等待10秒后，客户端1和2才会输出。

文件夹`/test1`

**三种并发模型**

* 多进程

* 多线程

* IO复用

**IO多路复用**

* 通过一种机制，监听多个描述符(连接资源)。当描述符可读写了之后，就通知程序进行相应的读写操作。

IO复用方式

* `select` 最古老，性能最差

* `poll`

* Linux`epoll`  其他平台`kqueue`

PHP内置函数`stream_select`函数使用`select`这种多路复用的方法。

#### IO多路复用

```
<?php
# 设置时区
date_default_timezone_set('Asia/Shanghai');
$master = [];

# 指定IP和端口
$socket = stream_socket_server("tcp://0.0.0.0:8000", $errno, $errstr);

$master[] = $socket;

# 守护进程都是从死循环开始
while (1) {
    $read = $master;
    $_w = $_e = NULL;
    
    # 监听$read中的$socket资源，如果有可读资源立刻返回可读资源的数量，如果有可读资源就填充到$read这个变量中
    # 第一个参数监听读
    # 第二个参数监听写
    # 第三个参数监听异常
    # 第四个参数设置超时时间，这里要等待五秒
    $mod_fd = stream_select($read,$_w ,$_e, 5);

    # 如果没有可读资源，则返回到while循环第一句
    if(!$mod_fd) continue;
    
    # 取出可读资源的keys
    $fds = array_keys($read);
    foreach($fds as $i){
        
        # 这里判断证明有连接过来
        if ($read[$i] === $socket) {
            $conn = stream_socket_accept($socket);
            
            # 这里赋值使得while循环第一行$read变量中有两种资源类型，一种是$socket一种是$conn连接
            $master[] = $conn;
        } else {

            # else里面就是读写数据
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
                # 将当前时间发送给客户端
                fwrite($conn, "Hello! The time is ".date("n/j/Y g:i a")."\n");
                # 返回客户端发送数据
                fwrite($read[$i], "You have sent :[".$sock_data."]\n");
                fclose($read[$i]);
                $key_to_del = array_search($read[$i], $master);
                unset($master[$key_to_del]);
            }
        }
    }
}
```

也可以用两个客户端来模拟，看是否会和之前一样的结果。

文件夹`/test2`

#### Workerman框架应用

socket服务框架：`Workerman` 和 `swoole`

使用`Workerman`需要安装扩展

* pcntl 写多进程的

* event或libevent 才能写epoll或kqueue

然后下载workerman框架

文件夹`/workerman`

```
<?php
use Workerman\Worker;

require_once './workerman/Autoloader.php';

// 创建一个Worker监听2345端口，使用http协议通讯
$http_worker = new Worker("http://0.0.0.0:2345");

// 启动4个进程对外提供服务
$http_worker->count = 4;

// 接收到浏览器发送的数据时回复hello world给浏览器
$http_worker->onMessage = function($connection, $data)
{
    // 向浏览器发送hello world
    $connection->send('hello world');
};

// 运行worker
Worker::runAll();
```

#### SWOOLE

文件夹中两个类  

`swoole_server.php` `swoole_client.php`

#### 聊天室

项目目录`websocket`

1.
* `php -S 127.0.0.1:9901 -d ./`


2.
* `php server.php`


