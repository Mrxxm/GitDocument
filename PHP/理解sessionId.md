在登录注册中，我们通过设置`session`来保存用户信息。

当我们执行设置`session`的操作时，也就是浏览器访问一个页面时，`php`发现在`cookie`里面没有`sessionid`这个值，就会产生一个`sessionid`出来，同时对应一个服务器里面的`session`文件。然后通过`cookie`传给浏览器（通过`cookie`），下次浏览器再访问页面的时候，就会把这个`sessionid`给带上（也是`cookie`），然后`php`通过这个`cookie`找到对应的`session`文件，读取`session`的值。

`php.ini`中设置了相关的session信息。

* 查看命令：`php -i | grep php.ini`

通过实例理解：

* safari浏览器：

```
test2.php

<?php
session_start();
$_SESSION['token'] = 1;
```

* `chrome`浏览器：

```
test2.php
	
<?php
session_start();
$_SESSION['token'] = 2;        
```

* `safari`浏览器 — `chrome`浏览器：

浏览器通过`token`这个`key`获取了不同的值，`safari`输出1，`chrome`输出2。

```
test1.php

<?php
session_start();
echo $_SESSION['token'];
```

#### 广义的`session`：

* 理解为一种保存`key-value`的机制

从key的方面看：

* `sessionid`(后端使用`session`缓存) — `token`(后端使用其他缓存如`redis`，需要自动生成)

* `sessionid`客户端请求服务端的时候，服务端`setcookie()`，就可以在`http`头里面设置`sessionid`这个`key`和对应的值。客户端`cookie`会将这个保存住。(服务端在设置`session`时，自动在浏览器`cookie`中生成保存`sessionid`)

* `token`在`bs`模式中，可以通过后端设置`cookie`实现，在`cs`模式中，可以通过接口返回到前端实现。

* `token`需要手动在`http`头里面或者`url`后设置`token`这个字段，服务器收到请求之后再从`header`头里面或者`url`中取出`token`进行验证。


```
总结：使用session，生成sessionId帮助区分不同存储空间，实现后端使用相同key取得不同value值。
     使用token，需要生成不同token值不重复，实现后端根据token作key取得不同value值。
```

— by _啃肉 
     20-02-28