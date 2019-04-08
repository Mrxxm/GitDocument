## Redis基础

#### redis简介

缓解甚至接管数据库压力。

如何查看数据库压力

查看数据库进程：`show processlist`

将常用的，查询条件复杂的数据缓存在Redis中，可以显著减少数据库的读写，从而降低数据库的压力。

操作具有**原子性**：保证了数据的可靠，错误操作的回滚，支持了竞争类业务的实现。

Redis的优势：

* 常驻内存，读写性能优越

* 支持多种数据格式，能实现多种业务需要

* 可以自动保存到硬盘，系统重启后恢复

#### Redis安装和配置

* 查看进程

`sudo lsof -nP -iTCP -sTCP:LISTEN`

* mac的安装目录

```
➜  bin pwd
/usr/local/opt/redis/bin
➜  bin ll
total 5520
-r-xr-xr-x  1 xuxiaomeng  admin    71K Dec  5  2017 redis-benchmark
-r-xr-xr-x  1 xuxiaomeng  admin   852K Dec  5  2017 redis-check-aof
-r-xr-xr-x  1 xuxiaomeng  admin   852K Dec  5  2017 redis-check-rdb
-r-xr-xr-x  1 xuxiaomeng  admin   132K Dec  5  2017 redis-cli
lrwxr-xr-x  1 xuxiaomeng  admin    12B Dec  5  2017 redis-sentinel -> redis-server
-r-xr-xr-x  1 xuxiaomeng  admin   852K Dec  5  2017 redis-server
```

* 进入redis客户端

```
➜  etc redis-cli
127.0.0.1:6379>
```

* 守护线程模式，在redis.conf文件中将`daemonize`改为yes

* 登录命令

```
redis-cli
redis-cli -h ip地址 -p 端口号
```

* Linux下使用守护进程模式启动redis服务后如何退出？

`redis安装目录/bin/redis-cli –p 端口号 shutdown`

#### PHP连接redis

注意两点：防火墙、IP绑定

安装扩展时注意：php的位数、php的线程是否安全、php的版本

编译安装：

* wget命令下载扩展包.tgz

* tar zxf 扩展包.tgz 解压

* 进入解压后的文件，进行编译安装

* 运行命令：`phpize` 有可能需要打全路径，或者需要安装依赖

* 运行命令：`./confiure --with-php-config`

* 运行命令：`make`

* 运行命令：`make install`,最后得到.so文件

* 需要在php.ini文件中，添加扩展的目录，添加`extension=xxx.so`

#### PHP操作key-value型数据

* set()

`$redis->set('age', 20);`

* get()

`$age = $redis->get('age');`

* del()

`$redis->del('age');`

* exists()

`$isAgeExist = $redis->exist('age');`

* setnx() 如果键不存在，则添加，如果存在，则不操作

`$res = $redis->setnx('age', 20);`

键名一般按照模块从大到小来设计，以冒号分隔，如`user:age:1`

举例：PV量统计

```
<?php

$redis = new Redis();

$redis->connect('127.0.0.1', 6379);

$pvKey = 'libdata:pv:' . data('Y-m-d');

if (!$redis->exists($pvKey)) {
    $redis->set($pvKey, 1);
} else {
    $redis->incrBy($pvKey, 1);
}
```


#### hash型数据

* hset()

`$redis->hSet('zhangsan', 'age', 20);`

* hget()

`$age = $redis->hGet('zhangsan', 'age');`

* hdel()

`$redis->hDel('zhangsan', 'age');`

* hexists()

`$isAgeExist = $redis->hExists('zhangsan', 'age');`

* hsetnx()

`$res = $redis->hSetNx('zhangsan', 'age', 20);`

缓存独立于数据库之外，并不是所有数据都接收延迟

要时刻注意数据的一致性

#### list型数据

类似php中的数组数据类型

* lset()

`$redis->lSet('list', 1, 4);` 1代表位置

```
$arr[1] = 4;
```

* llen()

`$len = $redis->lLen('list');`

```
count($arr);
```

* lrange()

`$arr = $redis->lRange('list', 0, 1);` 取数据从开始位置到结束位置

```
array_slice($arr, $offset, $length); 取数据从开始位置加上长度
```

* ltrim()

`$redis->lTrim('list', 0, 1);` 保留选中数据，其他数据将被抛弃

* lpush()、rpush() 如果list不存在，则创建一个list

`$redis->rPush('list', 1);`

```
array_push() 数组末尾插入数据

array_unshift() 数组头部插入数据
```

* lpop()、rpop()

`$redis->lPop('list');`

```
array_pop() 数组末尾取出数据

array_shift() 数组头部取出数据
```

#### set型数据

无序集合，list为有序集合

* sadd()

`$redis->sAdd('test', 'a');`

* smembers()

`$arr = $redis->sMembers('test');` 集合以数组方式返回

* scard()

`$redis->sCard('test');` 返回集合中成员个数

* spop()

`$redis->sPop('test');` 随机拿掉集合中的成员

* sdiff()

`$redis->sDiff('test1', 'test2');` 将集合中重复的成员去除，留下test1中的数据

