#### CURL简介

curl是利用URL语法在`命令行`方式下工作的开源文件传输工具

curl优点

* 基于`libcurl` `libcurl`是linux组件

* 返回代码

* 多协议支持

* 支持`multipart/form-data`

#### PHP中的CURL与安装

通过PHP扩展方式，使用扩展函数实现

Linux下安装PHP时配置：`./configure --with-curl`(需libcurl包)

Linux下载Pecl扩展包手动安装

#### CURL基本函数

* demo1 (返回页面字符串长度)

```
<?php
$url = 'http://www.imooc.com';
$ch = curl_init($url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,0);
curl_setopt($ch,CURLOPT_HEADER,FALSE);
curl_setopt($ch,CURLOPT_TIMEOUT,120);
$html = curl_exec($ch);
curl_close($ch);
var_dump($html);
```

* `curl_init($url)` 初始化CURL会话，返回资源类型

* `curl_setopt($ch, $option, $value)` 为curl设置相应常量

```
* $ch 由curl_init()返回的CURL句柄
* $option 需要设置的CURLOPT_XXX选项
* $value 将设置在option选项上的值
```

* `curl_setopt_array($ch, $option)` 为CURL会话批量设置选项

```
* $options 一个array用来确定将被设置的选项及其值
```

例：

```
$setopt_array = array(
   CURLOPT_RETURNTRANSFER => TRUE,
   CURLOPT_CONNECTTIMEOUT =>0
);
curl_setopt_array($ch,$setopt_array);
```

* `curl_reset($ch)` 重置一个libcurl会话句柄的所有选项


* `curl_error($ch)` 返回当前会话最后一次错误的字符串

* `curl_getinfo($ch)` 获取一个CURL连接资源句柄的信息

返回的值信息：

![](https://img1.doubanio.com/view/photo/l/public/p2554343767.jpg)

* `curl_close($ch)` 关闭CURL会话


#### CURL预定义常量

bool类

* `CURLOPT_HEADER` 将头文件的信息作为数据流输出(一般设为false)

* `CURLOPT_POST` 启用时会发送一个常规的POST请求(默认发送get请求)

* `CURLOPT_SSL_VERIFYPEER` 禁用后CURL将终止从服务端进行验证(一般设置为false)

* `CURLOPT_SSL_VERIFYHOST` 进行SSL验证域名(一般设置为false)

* `CURLOPT_RETURNTRANSFER` 启用后返回执行结果(一定要设置成true)

int类

* `CURLOPT_CONNECTTIMEOUT` 在发起连接等待的时间(一般设置为0)

* `CURLOPT_INFILESIZE` 设置上传文件的大小限制，字节为单位

* `CURLOPT_PORT` 用来指定连接端口

* `CURLOPT_PROXYPORT` 代理服务器的端口

* `CURLOPT_TIMEOUT` 设置CURL允许执行的最长秒数

#### CURLFile类基本操作

专门针对CURL文件上传设置的类

CURLFile应该与选项`CURLOPT_POSTFIELDS`一同使用用于上传

属性：

* `name` 待上传文件的名称

* `mime` 文件的mime类型

* `postname` 上传数据中的文件名称

方法：

* `CURLFile::__construct($filename, $mime, $postname);` 创建对象

* `CURLFile::getFilename` 获取被上传文件的文件名

* `CURLFile::getMimeType` 获取被上传文件的MIME类型

* `CURLFile::getPostFilename` 获取POST请求时使用的文件名

```
$curl_file = new CURLFile('E:pic\test.jpg');
$post_pic = array('upload_pic' => $curl_file);
$ch = curl_init();
curl_setopt($ch,CURLOPT_POST,true);
curl_setopt($ch,CURLOPT_POSTFIELDS,$post_pic);
```

实例：

curl.php

```
$url = 'http://localhost/upload.php';
$cfile = new CURLFile(realpath('test.jpg'));

$imgdata = array('myimage' => cfile);

$ch = curl_init($url);
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
curl_setopt($ch,CURLOPT_POST,TRUE);
curl_setopt($ch,CURLOPT_POSTFIELDS,$imgdata);

$result = curl_exec($ch);
curl_close($ch);
var_dump($result);
```

upload.php

```
<?php
// $file = $_FILES['myimage'];
// echo serialize($file);
// exit;
require './class_upload.php';
$up = new FileUpload();
$up->set('path','./pic/');
$up->set('maxsize',10485760);
$up->set('allowtype',array('gif','png','jpg'));
$up->set('israndname',true);
$ret = $up->upload("myimage");
$error = $up->getErrorMsg();
echo $error;
```

#### 实战抓取百度百科信息1

```
// 自动抓取html代码并返回
function setCurl($url,$proxy_flag=FALSE,$proxy=array()){
    $ch = curl_init($url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,0);
    curl_setopt($ch,CURLOPT_HEADER,FALSE);
    curl_setopt($ch,CURLOPT_TIMEOUT,120);
    if('https' == substr($url,0,5)){
      curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
      curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
    }
    if($proxy_flag){
      curl_setopt($ch,CURLOPT_PROXY,$proxy['name'].':'.$proxy['pass'].'@'.$proxy['host']);
      curl_setopt($ch,CURLOPT_PROXYPORT,$proxy['port']);
    }
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
  }
  
$url = '';
var_dump(setCurl($url));
```

#### 实战抓取百度百科信息2

```
// 去除html代码中的空格，使其成为真正字符串
function trimAll($str){
     $pattern = array('',' ',' ',"\t","\n","\r","&nbsp;");
     $str = str_replace($pattern,'',$str);
     $str = preg_replace('/\x{00a0}/u','',$str);
     return $str;
   }
```

```
function handleBaikeInfo($baike_info){
     $basic_info = array();
     preg_match('/出品公司(.*)\<\/dd\>/U',$baike_info,$match);
     $basic_info['chupin'] = $match[1];
     preg_match('/导演(.*)\<\/dd\>/U',$baike_info,$match);
     $basic_info['director'] = $match[1];
     preg_match('/主演\<\/dt\>(.*)\<\/dd\>/U',$baike_info,$match);
     $basic_info['actor'] = $match[1];
     // 清洗数组中的html代码
     foreach($basic_info as $key => $value){
       $basic_info[$key] = strip_tags($value);
     }
     var_dump($basic_info);
   }
   
   $url = 'https://baike.baidu.com/item/%E6%88%98%E7%8B%BC%E2%85%A1/20794668?fr=aladdin&fromid=17196087&fromtitle=%E6%88%98%E7%8B%BC2';
   $html = setCurl($url);
   $html = trimAll($html);
   handleBaikeInfo($html);
```


#### API接口简介

```
function api($url,$params=array(),$method='GET',$header=array()){
    $opts = array(
      CURLOPT_TIMEOUT => 30,
      CURLOPT_RETURNTRANSFER => TRUE,
      CURLOPT_SSL_VERIFYHOST => FALSE,
      CURLOPT_SSL_VERIFYPEER => FALSE,
      CURLOPT_HTTPHEADER => $header,
      CURLOPT_HEADER => FALSE
    );
    switch(strtoupper($method)){
      case 'GET':
        $opts[CURLOPT_URL] = $url.'?'.http_build_query($params);
        break;
      case 'POST':
        $opts[CURLOPT_URL] = $url;
        $opts[CURLOPT_POST] = TRUE;
        $opts[CURLOPT_POSTFIELDS] = $params;
        break;
    }
    $ch = curl_init();
    curl_setopt_array($ch,$opts);
    $result = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    if($error){
      echo 'curl执行出错';
    }
     return $result;
  }

  $url = 'http://localhost/get_member_name.php';
  $params = array('id'=>2);
  $ret = api($url,$params);
  echo $ret;

```

