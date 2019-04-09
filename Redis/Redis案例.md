## Redis案例

#### 优化案例简介

类似pc端新闻网站。

#### 获取天气优化

接管减少对天气接口的请求次数

1.获取用户IP

2.匹配用户的城市

3.通过城市获取天气

Redis类型：`HASH`

* 每请求一次，对天气进行一次重新的赋值(可以设为每十分钟执行一次)

```
<?php

include_once 'index.php';

$redis = CacheRedis::getInstance();

$cityList = ['北京', '上海'];
$weatherList = ['晴', '雨', '阴', '雪'];

foreach($cityList as $city) {
    
    // 模拟请求到的天气
    $weather = $weatherList[mt_rand(0, count($weatherList) - 1)];
    
    $redis->hSet('libdata:cityweather', $city, $weather);
}
```

* 控制器中实现

```
public function getWeather()
{
    $city = App::get('city', '');
    
    $redis = CacheRedis::getInstance();
    
    $weather = $redis->hGet('libdata:cityweather', $city);
    
    $arr = ['city' => $city, 'weather' => $weather];
    
    return json_encode($arr);
}
```

#### pv优化

接管网站的pv访问量。

直接对数据库进行操作，数据库的写操作要是锁表，会造成网站非常的卡

`incrBy()`来实现

Redis类型：`String`

控制器中, 如果键 `key` 不存在， 那么键 `key` 的值会先被初始化为 `0`, 然后再执行 `INCRBY` 命令 

```
public function addpv()
{
    $redis = CacheRedis::getInstance();
    
    $redis->incrBy('libdata:pv', 1);
    
    return ;
}

```

获取当前pv

```
public function index()
{
    $redis = CacheRedis::getInstance();
    
    $pv = $redis->get('libdata:pv');
    
    if (!$pv) {
        
        $pv = 1; 
    }
    
    return $pv;
}
```

添加脚本，定时将缓存中的pv存到数据库中。

QPS：每秒钟处理的查询量达到2000以上，`incrBy()`也会有一定压力。

TPS：每秒处理的消息数。

#### Favorite-pv优化

接管最多访问的分类标签。

访问某个分类的新闻页面，对所属分类的pv+1，传入`uid`和`nid`

`uid`：用户id
`nid`：新闻id
`tid`：分类id

`hIncrBy()`来实现

Redis结构：`1: 1->5, 2->8, 3->4` 用户1：对`tid`为1的pv为5

Redis类型：HASH

控制器

```
pubilc function addUserPv($uid, $tid)
{
    $redis = CacheRedis::getInstance();
    
    $key = 'libdata:userpv:' . $uid;
    
    $redis->hIncrBy($key, $tid, 1);
    
    return;
}
```

读数据

```
public function getMostPvType($uid)
{
    $redis = CacheRedis::getInstance();
    
    $key = 'libdata:userpv:' . $uid;
    
    // 非常少的数据可以hgetall(),返回一个关联数组
    $data = $redis->hGetAll($key);
    
    if (!$data) {
        $tid = 1;
    } else {
        arsort($data); // 按值倒序
        
       $keys = array_keys($data);
       
       $tid = current($keys); // 取到第一个数据
       // 然后返回tid代表的分类
       return ;
    }
}
```

#### 分类预览优化

接管分类下数据变动，缓存分类数据，对单个分类数据进行更新，不对全局分类数据进行遍历更新。

原数据结构：

```
[
    [
    'id' => 1,
    'name' => '国内',
    'count' => 1020,
    ],
    [
    'id' => 2,
    'name' => '军事',
    'count' => 994,
    ],
    [
    'id' => 3,
    'name' => '科技',
    'count' => 998,
    ]
]
```

新闻添加成功后数据结构：

```
[
    'id' => 1,
    'name' => '国内',
    'count' => 0,
]
```

控制器添加新闻

```
public function addNews()
{
    // 新闻添加后
    $redis = CacheRedis::getInstance();
    
    $key = 'libdata:tidcount:' . $tid;
    
    $redis->hSet($key, 'id', $tid);
    $redis->hSet($key, 'name', $name);
    $redis->hIncrBy($key, 'count', 1);
    
}
```

修正分类新闻数量脚本，也是定时执行。

```
// 数据库查询出分类数据
$newsTypes = 

if (!empty($newsTypes)) {
    foreach($newsTypes as $index => $type)  {
    $tid = $type['id'];
    $key = 'libdata:tidcount:' . $tid;
    $name = $type['name'];
    $count = 数据库中根据tid查询
    
    $redis->hSet($key, 'id', $tid);
    $redis->hSet($key, 'name', $name);
    $redis->hSet($key, 'count', $count);
    }
}
```

#### 新闻分类优化

#### 评论数量优化

控制器

```
// 评论添加后
$redis = CacheRedis::getInstance();

$key = 'libdata:nid:' . $nid;

$redis->hIncrBy($key, 'commentcount', 1);

```

为每条新闻获取评论数

```
foreach ($list as $index => $news) {

    $key = 'libdata:nid:' . $news['id'];
    
    $list[$index]['commentcount'] = $redis->hGet($key, 'commentcount');
    
}
```

同样添加修复脚本使每条新闻评论数能够对应

```
$redis = CacheRedis::getInstance();

// 要基于id而不是limit去遍历新闻表
$mysqli = DB::getInstance();

$res = $mysqli->query("select * from `news` limit 10");

$newList = $res->fetch_all(MYSQLLI_ASSOC);

if (!empty($newList)) {
    foreach($newList as $news) {
    
        $nid = $new['id'];
    
        $res = $mysqli->query("select count(*) from `news_comment` where nid = {$nid}");
        
        if (!$res) {
            
            $count = 0;
            
        } else {
        
            $arr = $res->fetch_assoc();
            
            $count = $arr['c'];
        }
        
        $arr = $res->fetch_assoc();
        
        $key = 'libdata:nid:' . $nid;
        
        $redis->hSet($key, 'commentcount', $count);
        
    }
}
```

#### 分页优化

