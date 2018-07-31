## `spl_autoload_register`和`__autoload`

### 1.`__autoload()`

当PHP引擎遇到试图实例化未知类的操作时，会调用__autoload()方法，并将类名当做字符串参数传递给它。

* index.php

```php
<?php

function __autoload($class) {
    $file = $class . '.php';
    require_once $file;
}

$text = new test();
```

* test.php

```php
<?php

class test {
  public function __construct() {
     echo "this is test!";
  }
}

```

输出：

	`this is test!`

当在index.php中初始化test时，因为找不到对应类，就会调用`__autoload()`引入对应文件。
但是，因为`__autoload()`在一个进程中只能定义它一次。当我们在进行项目合并的时候，如果出现多个`autoload()`需要将其合并为一个函数。那么，出现了`spl_autoload_register()`。

### 2.`spl_autoload_register()`

上述代码可以写成：

* index.php

```php
<?php

function autoload_test($class) {
    $file = $class . '.php';
    require_once $file;
}

spl_autoload_register("autoload_test");

$text = new test();

```

* test.php

```php
<?php

class test {
  public function __construct() {
     echo "this is test!";
  }
}

```

输出：

`this is test!`

使用此函数可以生成一个`__autoload()`队列，可以注册多个函数。
代码如下:

* index.php

```php
<?php
define('ROOT_DIR', dirname(__DIR__));

function autoload_test($class) {
    $file = $class . '.php';
    require_once $file;
}
function autoload_vos($class) {
    $file = ROOT_DIR . '/www/test/' . $class . '.php';
    require_once $file;
}

spl_autoload_register("autoload_test");
spl_autoload_register("autoload_vos");

$text = new test();
echo "<br />";
$text = new index();

```

* test.php

```php
<?php

class test {
  public function __construct() {
     echo "this is test!";
  }
}

```

* /test/index.php

```php
<?php

class index {
  public function __construct() {
     echo "this is index!";
  }
}

```

输出：

```
this is test!
this is index!
```

可以注册多个函数。  
函数执行顺序是先加载的先执行。

### 3.如果出现类继承情况  
假设文件结构如下：

```
|-test.php
|-test1.php
|-test2.php
```

代码如下：

* index.php

```php
<?php
function loadprint( $class ) {
	echo $class."<br />";   //code one
    $file = $class . '.php';  
    if (is_file($file)) {  
      require_once($file);  
    } 
} 

spl_autoload_register( 'loadprint' ); 
 
$obj = new test2();
$obj->hello();

```

* test.php

```php
<?php

class test {
  
  public function hello() {
    echo "im test";
  }
}

```

* test1.php

```php
<?php

class test1 extends test {
  
}

```

* test2.php

```php
<?php

class test2 extends test1 {
  
}

```

输出：

```
test2
test1
test
im test
```

这里自动加载的过程中对继承关系也同样适用。