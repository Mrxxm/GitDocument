## Symfony2核心文件及目录结构

* composer.json (composer的定义文件)

* app/AppKernel.php (安装的第三方包需要在其中启用)

* app/cache/ (缓存文件，类的映射关系。开发环境自动更新缓存)

* app/config/config.php (配置目录，yml格式，用四个空格表示层级)

* app/config/routing.php (路由参数)

* app/config/secuirty.php (安全参数)

* app/logs/ (日志文件目录)

* bin/ (执行文件)

* src/ (源代码目录。bundle的概念，不同的bundle负责不同功能。)

* src/Acme/DemoBundle/Controller (控制器)

* src/Acme/DemoBundle/Dependencyinjection (依赖注入)

* src/Acme/DemoBundle/EventListener (动态事件监听)

* src/Acme/DemoBundle/Resources (模板，Twig模板，类的继承来管理模板)

* src/Acme/DemoBundle/Twig (模板的扩展)

* vendor/ (第三方包，由composer来维护) 

* web/ (唯一暴露在外的目录，app.php[生产环境]单一入口的文件 app_dev.php[开发环境])

* app/check.php (生成配置报告，运行: `$ php app/check.php`)

* web/config.php (帮助配置的文件，`http://localhost/config.php`)