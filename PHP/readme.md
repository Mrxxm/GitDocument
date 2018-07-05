## php入门

![](https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1530768945893&di=b6e9ed67932327c795bf9c8955ff7ffc&imgtype=0&src=http%3A%2F%2Fs2.51cto.com%2Fwyfs02%2FM01%2F6C%2F13%2FwKiom1U_HIqhsyLOAADOTJeDXWM434.jpg)

```
<?php

// 重定向到test/index.php
header("Location:test/index.php");

// 重定向停留三秒
header("Refresh:3; url=test/index.php");

// 禁用缓存
header("Expires:-1");
header("Cache-Control:no_cache");
header("Pragma:no_cache");

// 定义文件下载
header("Content-type:application/octet-stream");
header("Accept-Ranges:bytes");
header("Accept-Length:$file_size");
header("Content-Disposition:attachment;filename=".$file_name);
```