## Redis消息队列

#### 消息队列简介

消息的顺序集合

常用场景和解决问题：

* 应对流量峰值

* 异步消费(不定速的插入，生产和匀速的处理、消费)

* 解耦应用(不同来源的生产和不同去向的消费)

网站首页的pv统计和查看(传统方式)：

```
UserA访问首页 -> ajax到控制器 -

UserB访问首页 -> ajax到控制器 -->> Mysql对每次请求 update pv + 1 -> 查看时 select pv

UserC访问首页 -> ajax到控制器 -
```

存在的问题：update需要锁表，数据库访问会越来越慢

网站首页的pv统计和查看(Redis消息队列方式)：

```
UserA访问首页 -> ajax到控制器 -

UserB访问首页 -> ajax到控制器 -->> Redis每次请求rpush pvlog -> 脚本匀速处理 pvlog set pv 查看时 get pv 

UserC访问首页 -> ajax到控制器 -
```

#### MySQL方式实现pv

页面

```
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Document</title>
</head>
<body>
  	<h1>欢迎访问首页</h1>
  	<p>当前pv量：0</p>
</body>
<script src="jquery.2.1.4.min.js"></script>
<script type="text/javascript">
	// 给pv量加一
	$.get('addpv.php', function () {
		
	})
</script>
</html>
```

脚本

```
// 给pv加一

//连接到数据库
$host = 'localhost';
$username = 'root';
$password = '9511134231';
$connection = mysql_connect($host, $username, $password);

//选择数据库
$database = 'test';
$selectedDb = mysql_select_db($database);

//构建查询语句
$query = "update pv set value=value+1 where name = 'index' limit 1";
//执行查询
$result = mysql_query($query);

mysql_close();
```

页面还需要获取pv

#### list实现消息队列原理

redis实现消息队列有两种方式：

* 基于list(数据结构和数组相似)

* 基于publish/subscribe(redis服务)

基于list的消息队列实现方式：

···

#### set方式实现pv

set方式实现pv

```
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->select(0);

$key = 'pv:index';
if ($redis->get($key) == false) {
    $redis->set($key, 0);
}

$redis->incrBy($key, 1);
```

#### list方式实现pv

往消息队列中存储

```
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->select(0);

$key = 'listpv:index';
$redis->rPush($key, 1);
```

脚本处理消息队列

```
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->select(0);

$key = 'listpv:index';
while(true) {
    if (false != $redis->lPop($key)) {
        $redis->incrBy('pv:index', 1);
    }
}
```

#### 发布订阅方式原理

基于publish、subscribe的消息队列实现方式

* 一个生产者，生产不同频道的内容。  

* 每个消费者，消费不同频道的内容。

* 频道固定，生产/消费者都不固定

![](https://img1.doubanio.com/view/photo/l/public/p2553202629.jpg)

#### 命令行模式中演示

1.首先开启服务端
2.开启两个客户端
3.两个客户端一个做监听操作，一个做发送操作

监听窗口

```
127.0.0.1:6379> SUBSCRIBE c1 c2
Reading messages... (press Ctrl-C to quit)
1) "subscribe"
2) "c1"
3) (integer) 1
1) "subscribe"
2) "c2"
3) (integer) 2
```

发送窗口

```
➜  ~ redis-cli
127.0.0.1:6379> PUBLISH c1 hello
(integer) 1
127.0.0.1:6379> PUBLISH c2 helloc2
(integer) 1
127.0.0.1:6379>
```

监听窗口

```
➜  ~ redis-cli
127.0.0.1:6379>
127.0.0.1:6379>
127.0.0.1:6379> SUBSCRIBE c1 c2
Reading messages... (press Ctrl-C to quit)
1) "subscribe"
2) "c1"
3) (integer) 1
1) "subscribe"
2) "c2"
3) (integer) 2

# 接收到的信息
1) "message"
2) "c1"
3) "hello"
1) "message"
2) "c2"
3) "helloc2"
```

监听窗口就类比消费者，发送窗口类比生产者。


#### 发布订阅方式-代码演示

监听者

```
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

// 超时控制(该设置为永不超时)
$redis->setOption(Redis::OPT_READ_TIMEOUT, -1);

$redis->subscribe(['c1'], function(Redis $instance, $channel, $message) {
    echo "receive message from {$channel} : {$message}\n";
});
```

发布者

```
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

// 返回值是监听这个管道客户端的个数
$redis = $redis->publish('c1', 111);

```

#### 发布订阅方式实现pv-新闻页面

* 功能一：统计首页，列表页，内容页的pv

* 功能二：统计浏览时长超过5秒的内容页

* 功能三：内容页的pv + 1，浏览时间超过5秒 + 5，浏览时间不超过5秒 - 1，生成内容页的质量分

TODO...
