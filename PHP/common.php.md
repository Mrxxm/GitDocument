## common.php

```php 
<?php

// 定义路径
define('ROOT_DIR', dirname(__DIR__));
echo ROOT_DIR; // /private/var

// 设置时区
date_default_timezone_set('Asia/Shanghai');

// 加载autoload
require_once ROOT_DIR.'/vendor/autoload.php';

// 加载env文件
if (getenv('IN_TESTING') === 'true') {
    \Codeages\Biz\Framework\Utility\Env::load(require ROOT_DIR.'/env.testing.php');
} else {
    \Codeages\Biz\Framework\Utility\Env::load(require ROOT_DIR.'/env.php');
}

// 设置php.ini中配置
ini_set('log_errors', 1);

// 根据env中配置，设置php.ini
if (env('DEBUG', false)) {
    error_reporting(E_ALL);
    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
    ini_set('display_startup_errors', 0);
    ini_set('display_errors', 0);
}

// 引入biz
$biz = new \Biz\AppBiz(require ROOT_DIR.'/config/biz.php');
$biz->boot();


```