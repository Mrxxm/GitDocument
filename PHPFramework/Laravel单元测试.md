## 环境

* 修改`phpunit.xml`

* 新建`.env.testing`文件

## 创建测试类

```
// 在 Feature 目录下创建一个测试类...
php artisan make:test UserTest

// 在 Unit 目录下创建一个测试类...
php artisan make:test UserTest --unit
```

## 执行单元测试

* 执行所有：`phpunit`

* 执行特定类：`phpunit tests/Feature/ExampleTest.php`

## `tests/TestCase.php`

* 使用服务提供商的boot方法引导程序包:`use CreatesApplication`

* 执行seed导入:`Artisan::call('db:seed');`

```
<?php

namespace Tests;

use App\Partner;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use App\User;

abstract class TestCase extends BaseTestCase
{
    // 使用服务提供商的boot方法引导程序包
    use CreatesApplication; 

    public $user;

    public function setUp()
    {
        parent::setUp();

//        Artisan::call('migrate');
        // 执行seed导入
        Artisan::call('db:seed');

        $this->user = Partner::firstOrFail();

    }
}
```

## `DatabaseMigrations`、`RefreshDatabase`

* 会删除数据库数据，重新执行迁移脚本