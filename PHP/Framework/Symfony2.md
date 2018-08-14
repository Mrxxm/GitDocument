# Symfony2

## 下载安装Symfony3.4

```
➜  www git:(master) ✗ sudo curl -LsS https://symfony.com/installer -o /usr/local/bin/symfony
Password:
➜  www git:(master) ✗ sudo chmod a+x /usr/local/bin/symfony
➜  www git:(master) ✗ symfony new my_project 3.4

 Downloading Symfony...

    6.2 MiB/6.2 MiB ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓  100%

 Preparing project...

 ✔  Symfony 3.4.14 was successfully installed. Now you can:

    * Change your current directory to /private/var/www/my_project

    * Configure your application in app/config/parameters.yml file.

    * Run your application:
        1. Execute the php bin/console server:start command.
        2. Browse to the http://localhost:8000 URL.

    * Read the documentation at https://symfony.com/doc

➜  my_project git:(master) ✗ composer update
Loading composer repositories with package information
Updating dependencies (including require-dev)
Package operations: 3 installs, 18 updates, 0 removals
  - Updating symfony/polyfill-ctype (v1.8.0 => v1.9.0): Downloading (100%)         
  - Updating symfony/polyfill-mbstring (v1.8.0 => v1.9.0): Downloading (100%)         
  - Updating twig/twig (v1.35.4 => v2.5.0): Downloading (100%)         
  - Updating paragonie/random_compat (v2.0.17 => v9.99.99): Downloading (100%)         
  - Updating symfony/polyfill-php70 (v1.8.0 => v1.9.0): Downloading (100%)         
  - Updating symfony/polyfill-util (v1.8.0 => v1.9.0): Downloading (100%)         
  - Updating symfony/polyfill-php56 (v1.8.0 => v1.9.0): Downloading (100%)         
  - Updating symfony/polyfill-intl-icu (v1.8.0 => v1.9.0): Downloading (100%)         
  - Updating symfony/polyfill-apcu (v1.8.0 => v1.9.0): Downloading (100%)         
  - Updating doctrine/inflector (v1.1.0 => v1.3.0): Loading from cache
  - Updating doctrine/collections (v1.4.0 => v1.5.0): Loading from cache
  - Updating doctrine/cache (v1.6.2 => v1.7.1): Loading from cache
  - Updating doctrine/annotations (v1.4.0 => v1.6.0): Downloading (100%)         
  - Installing doctrine/reflection (v1.0.0): Downloading (100%)         
  - Installing doctrine/event-manager (v1.0.0): Downloading (100%)         
  - Installing doctrine/persistence (v1.0.0): Downloading (100%)         
  - Updating doctrine/common (v2.7.3 => v2.9.0): Downloading (100%)         
  - Updating doctrine/instantiator (1.0.5 => 1.1.0): Downloading (100%)         
  - Updating doctrine/dbal (v2.5.13 => v2.8.0): Downloading (100%)         
  - Updating doctrine/orm (v2.5.14 => v2.6.2): Downloading (100%)         
  - Updating composer/ca-bundle (1.1.1 => 1.1.2): Downloading (100%)         
Writing lock file
Generating autoload files
> Incenteev\ParameterHandler\ScriptHandler::buildParameters
Updating the "app/config/parameters.yml" file
> Sensio\Bundle\DistributionBundle\Composer\ScriptHandler::buildBootstrap
> Sensio\Bundle\DistributionBundle\Composer\ScriptHandler::clearCache

 // Clearing the cache for the dev environment with debug                       
 // true                                                                        

                                                                                
 [OK] Cache for the "dev" environment (debug=true) was successfully cleared.    
                                                                                

> Sensio\Bundle\DistributionBundle\Composer\ScriptHandler::installAssets

 Trying to install assets as relative symbolic links.

                                                                                
 [OK] No assets were provided by any bundle.                                    
                                                                                

> Sensio\Bundle\DistributionBundle\Composer\ScriptHandler::installRequirementsFile
> Sensio\Bundle\DistributionBundle\Composer\ScriptHandler::prepareDeploymentTarget

➜  my_project git:(master) ✗ bin/console 
Symfony 3.4.14 (kernel: app, env: dev, debug: true)

Usage:
  command [options] [arguments]

Options:
  -h, --help            Display this help message
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -e, --env=ENV         The Environment name. [default: "dev"]
      --no-debug        Switches off debug mode.
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Available commands:
  about                                   Displays information about the current project
  help                                    Displays help for a command
  list                                    Lists commands
 assets
  assets:install                          Installs bundles web assets under a public directory
 cache
  cache:clear                             Clears the cache
  cache:pool:clear                        Clears cache pools
  cache:pool:prune                        Prunes cache pools
  cache:warmup                            Warms up an empty cache
 config
  config:dump-reference                   Dumps the default configuration for an extension
 debug
  debug:autowiring                        Lists classes/interfaces you can use for autowiring
  debug:config                            Dumps the current configuration for an extension
  debug:container                         Displays current services for an application
  debug:event-dispatcher                  Displays configured listeners for an application
  debug:form                              Displays form type information
  debug:router                            Displays current routes for an application
  debug:swiftmailer                       [swiftmailer:debug] Displays current mailers for an application
  debug:twig                              Shows a list of twig functions, filters, globals and tests
 doctrine
  doctrine:cache:clear-collection-region  Clear a second-level cache collection region
  doctrine:cache:clear-entity-region      Clear a second-level cache entity region
  doctrine:cache:clear-metadata           Clears all metadata cache for an entity manager
  doctrine:cache:clear-query              Clears all query cache for an entity manager
  doctrine:cache:clear-query-region       Clear a second-level cache query region
  doctrine:cache:clear-result             Clears result cache for an entity manager
  doctrine:cache:contains                 Check if a cache entry exists
  doctrine:cache:delete                   Delete a cache entry
  doctrine:cache:flush                    [doctrine:cache:clear] Flush a given cache
  doctrine:cache:stats                    Get stats on a given cache provider
  doctrine:database:create                Creates the configured database
  doctrine:database:drop                  Drops the configured database
  doctrine:database:import                Import SQL file(s) directly to Database.
  doctrine:ensure-production-settings     Verify that Doctrine is properly configured for a production environment
  doctrine:generate:crud                  [generate:doctrine:crud] Generates a CRUD based on a Doctrine entity
  doctrine:generate:entities              [generate:doctrine:entities] Generates entity classes and method stubs from your mapping information
  doctrine:generate:entity                [generate:doctrine:entity] Generates a new Doctrine entity inside a bundle
  doctrine:generate:form                  [generate:doctrine:form] Generates a form type class based on a Doctrine entity
  doctrine:mapping:convert                [orm:convert:mapping] Convert mapping information between supported formats
  doctrine:mapping:import                 Imports mapping information from an existing database
  doctrine:mapping:info                   
  doctrine:query:dql                      Executes arbitrary DQL directly from the command line
  doctrine:query:sql                      Executes arbitrary SQL directly from the command line.
  doctrine:schema:create                  Executes (or dumps) the SQL needed to generate the database schema
  doctrine:schema:drop                    Executes (or dumps) the SQL needed to drop the current database schema
  doctrine:schema:update                  Executes (or dumps) the SQL needed to update the database schema to match the current mapping metadata
  doctrine:schema:validate                Validate the mapping files
 generate
  generate:bundle                         Generates a bundle
  generate:command                        Generates a console command
  generate:controller                     Generates a controller
 lint
  lint:twig                               Lints a template and outputs encountered errors
  lint:xliff                              Lints a XLIFF file and outputs encountered errors
  lint:yaml                               Lints a file and outputs encountered errors
 router
  router:match                            Helps debug routes by simulating a path info match
 security
  security:check                          Checks security issues in your project dependencies
  security:encode-password                Encodes a password.
 server
  server:log                              Starts a log server that displays logs in real time
  server:run                              Runs a local web server
  server:start                            Starts a local web server in the background
  server:status                           Outputs the status of the local web server for the given address
  server:stop                             Stops the local web server that was started with the server:start command
 swiftmailer
  swiftmailer:email:send                  Send simple email message
  swiftmailer:spool:send                  Sends emails from the spool

```


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

## hello world

* 创建bundle 

```
➜  my_project git:(master) ✗ bin/console generate:bundle

                                            
  Welcome to the Symfony bundle generator!  
                                            

Are you planning on sharing this bundle across multiple applications? [no]: 

Your application code must be written in bundles. This command helps
you generate them easily.

Give your bundle a descriptive name, like BlogBundle.
Bundle name: Demo/WebBundle

In your code, a bundle is often referenced by its name. It can be the
concatenation of all namespace parts but it's really up to you to come
up with a unique name (a good practice is to start with the vendor name).
Based on the namespace, we suggest DemoWebBundle.

Bundle name [DemoWebBundle]: 

Bundles are usually generated into the src/ directory. Unless you're
doing something custom, hit enter to keep this default!

Target Directory [src/]: 

What format do you want to use for your generated configuration?

Configuration format (annotation, yml, xml, php) [annotation]: annotation

                     
  Bundle generation  
                     

> Generating a sample bundle skeleton into app/../src/Demo/WebBundle
  created ./app/../src/Demo/WebBundle/
  created ./app/../src/Demo/WebBundle/DemoWebBundle.php
  created ./app/../src/Demo/WebBundle/Controller/
  created ./app/../src/Demo/WebBundle/Controller/DefaultController.php
  created ./app/../tests/DemoWebBundle/Controller/
  created ./app/../tests/DemoWebBundle/Controller/DefaultControllerTest.php
  created ./app/../src/Demo/WebBundle/Resources/views/Default/
  created ./app/../src/Demo/WebBundle/Resources/views/Default/index.html.twig
  created ./app/../src/Demo/WebBundle/Resources/config/
  created ./app/../src/Demo/WebBundle/Resources/config/services.yml
> Checking that the bundle is autoloaded
FAILED
> Enabling the bundle inside app/AppKernel.php
  updated ./app/AppKernel.php
OK
> Importing the bundle's routes from the app/config/routing.yml file
  updated ./app/config/routing.yml
OK
> Importing the bundle's services.yml from the app/config/config.yml file
  updated ./app/config/config.yml
OK

                                                                   
  The command was not able to configure everything automatically.  
  You'll need to make the following changes manually.              
                                                                   

- Edit the composer.json file and register the bundle
  namespace in the "autoload" section:

```	

* 配置修改

**composer.json**

```
{
    "name": "xuxiaomeng/my_project",
    "license": "proprietary",
    "type": "project",
    "autoload": {
        "psr-4": {
            "AppBundle\\": "src/AppBundle",
            
            # 添加这一行后运行 $ composer update
            "Demo\\": "src/Demo"

```

**`$ bin/console debug:twig` 查看模板命名空间**

```
➜  my_project git:(master) ✗ bin/console debug:twig

Functions
---------

 * absolute_url(path)
 * asset(path, packageName = null)
 * asset_version(path, packageName = null)
 * constant(constant, object = null)
 * controller(controller, attributes = [], query = [])
 * csrf_token(tokenId)
 * cycle(values, position)
 * date(date = null, timezone = null)
 * dump()
 * expression(expression)
 * form(unknown?)
 * form_end(unknown?)
 * form_errors(unknown?)
 * form_label(unknown?)
 * form_rest(unknown?)
 * form_row(unknown?)
 * form_start(unknown?)
 * form_widget(unknown?)
 * include(template, variables = [], withContext = true, ignoreMissing = false, sandboxed = false)
 * is_granted(role, object = null, field = null)
 * logout_path(key = null)
 * logout_url(key = null)
 * max(args)
 * min(args)
 * path(name, parameters = [], relative = false)
 * profiler_dump(value, maxDepth)
 * profiler_dump_log(message, context = null)
 * random(values = null)
 * range(low, high, step)
 * relative_path(path)
 * render(uri, options = [])
 * render_*(strategy, uri, options = [])
 * source(name, ignoreMissing = false)
 * url(name, parameters = [], schemeRelative = false)

Filters
-------

 * abbr_class
 * abbr_method
 * abs
 * batch(size, fill = null)
 * capitalize
 * convert_encoding(to, from)
 * date(format = null, timezone = null)
 * date_modify(modifier)
 * default(default = "")
 * doctrine_minify_query
 * doctrine_pretty_query(highlightOnly = false)
 * doctrine_replace_query_parameters(parameters)
 * e(strategy = "html", charset = null, autoescape = false)
 * escape(strategy = "html", charset = null, autoescape = false)
 * file_excerpt(line, srcContext = 3)
 * file_link(line)
 * first
 * form_encode_currency(widget = "")
 * format(args)
 * format_args
 * format_args_as_text
 * format_file(line, text = null)
 * format_file_from_text
 * format_log_message(context)
 * humanize
 * join(glue = "")
 * json_encode(options, depth)
 * keys
 * last
 * length
 * lower
 * merge(arr2)
 * nl2br(is_xhtml)
 * number_format(decimal = null, decimalPoint = null, thousandSep = null)
 * raw
 * replace(from)
 * reverse(preserveKeys = false)
 * round(precision = 0, method = "common")
 * slice(start, length = null, preserveKeys = false)
 * sort
 * split(delimiter, limit = null)
 * striptags(allowable_tags)
 * title
 * trans(arguments = [], domain = null, locale = null)
 * transchoice(count, arguments = [], domain = null, locale = null)
 * trim(characterMask = null, side = "both")
 * upper
 * url_encode
 * yaml_dump(inline = 0, dumpObjects = false)
 * yaml_encode(inline = 0, dumpObjects = 0)

Tests
-----

 * constant
 * defined
 * divisible by
 * empty
 * even
 * iterable
 * none
 * null
 * odd
 * rootform
 * same as
 * selectedchoice

Globals
-------

 * app = object(Symfony\Bridge\Twig\AppVariable)

Loader Paths
------------

 --------------- ------------------------------------------------------------------------------ 
  Namespace       Paths                                                                         
 --------------- ------------------------------------------------------------------------------ 
  @Framework      vendor/symfony/symfony/src/Symfony/Bundle/FrameworkBundle/Resources/views/    
  @!Framework     vendor/symfony/symfony/src/Symfony/Bundle/FrameworkBundle/Resources/views/    
  @Security       vendor/symfony/symfony/src/Symfony/Bundle/SecurityBundle/Resources/views/     
  @!Security      vendor/symfony/symfony/src/Symfony/Bundle/SecurityBundle/Resources/views/     
  @Twig           vendor/symfony/symfony/src/Symfony/Bundle/TwigBundle/Resources/views/         
  @!Twig          vendor/symfony/symfony/src/Symfony/Bundle/TwigBundle/Resources/views/         
  @Swiftmailer    vendor/symfony/swiftmailer-bundle/Resources/views/                            
  @!Swiftmailer   vendor/symfony/swiftmailer-bundle/Resources/views/                            
  @Doctrine       vendor/doctrine/doctrine-bundle/Resources/views/                              
  @!Doctrine      vendor/doctrine/doctrine-bundle/Resources/views/                              
  @DemoWeb        src/Demo/WebBundle/Resources/views/                                           
  @!DemoWeb       src/Demo/WebBundle/Resources/views/                                           
  @Debug          vendor/symfony/symfony/src/Symfony/Bundle/DebugBundle/Resources/views/        
  @!Debug         vendor/symfony/symfony/src/Symfony/Bundle/DebugBundle/Resources/views/        
  @WebProfiler    vendor/symfony/symfony/src/Symfony/Bundle/WebProfilerBundle/Resources/views/  
  @!WebProfiler   vendor/symfony/symfony/src/Symfony/Bundle/WebProfilerBundle/Resources/views/  
                                                                                                
  (None)          app/Resources/views/                                                          
                  vendor/symfony/symfony/src/Symfony/Bridge/Twig/Resources/views/Form/          
 --------------- ------------------------------------------------------------------------------ 
```

**`Demo/Webbundle/Controller/DefaultController.php`**

```
<?php

namespace Demo\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        # 根据模板命名空间修改返回路径
        return $this->render('@DemoWeb/Default/index.html.twig');
    }
}
```

**`http://localhost/my_project/web/app_dev.php`**

```
hello world!
```

## 路由

当一个请求发送到您的应用程序，它包含一个确切的“资源”的客户端请求地址。该地址被称为URL（或URI），它可以是/contact、/blog/read-me或其它任何东西。下面是一个HTTP请求的例子：

```
GET /blog/my-blog-post
```

symfony路由系统的目的是解析url，并确定调用哪个控制器。整个过程是这样的：

1. 由Symfony的前端控制器（app.php）来处理请求。

2. symfony的核心（Kernel内核）要求路由器来检查请求。

3. 路由将输入的URL匹配到一个特定的路由,并返回路由信息，其中包括要执行的控制器信息。

4. Symfony内核执行控制器并最终返回Response对象。

![](http://www.newlifeclan.com/symfony/wp-content/uploads/sites/2/2014/12/request-flow.png)

路由是将一个输入URL转换成特定的工具来执行控制器。

#### 路由的四种配置

* Annotation(允许你在方法的上面用注释定义方法运行状态的功能)
* router.yml(Symfony2常用配置格式)
* router.xml
* PHP

#### 定义URL

* 静态URL
* 动态URL

**添加nginx配置**

```
server {
    listen      80;

    server_name my_project.cn;
    root /var/www/my_project/web;

    error_log /var/log/nginx/symfony.error.log;
    access_log /var/log/nginx/symfony.access.log;
    location / {
    index app_dev.php;
    #index app.php;
    try_files $uri @rewriteapp;
    }	

    location @rewriteapp {
    rewrite ^(.*)$ /app_dev.php/$1 last;
    #rewrite ^(.*)$ /app.php/$1 last;
    }  

    location ~ ^/(app|app_dev)\.php(/|$) {
    fastcgi_pass   127.0.0.1:9000;
    fastcgi_split_path_info ^(.+\.php)(/.*)$;
    include fastcgi_params;
    fastcgi_param  SCRIPT_FILENAME    $document_root$fastcgi_script_name;
    fastcgi_param  HTTPS              off;
    fastcgi_param HTTP_X-Sendfile-Type X-Accel-Redirect;
    # [改] 请根据程序的实际安装路径修改。该目录下存放的是私有的文件。
    fastcgi_param HTTP_X-Accel-Mapping /udisk=/var/www/symfony/app/data/udisk;
    fastcgi_buffer_size 128k;
    fastcgi_buffers 8 128k;
    }

    location ~ \.php$ {
        return 404;
    }
}

```

**访问`http://my_project.cn`**

```
Welcome to
Symfony 3.4.14
Your application is now ready. You can start working on it at: /private/var/www/my_project/

What's next?
Read the documentation to learn
How to create your first page in Symfony
```
#### 静态URL

**配置一**

```
class DefaultController extends Controller
{
    /**
     * @Route("/hello")
     */
    public function indexAction()
    {
        return $this->render('@DemoWeb/Default/index.html.twig');
    }
}
```

**`http://my_project.cn/hello`**

```
hello world!
```

#### 动态URL

**配置一**

* `requirements`限制`$page_num`传入的参数
* `defaults`定义默认值,路由访问`http://my_project.cn/page`

```
class DefaultController extends Controller
{
    /**
     * @Route("/page/{page_num}", defaults={"page_num":1}, requirements={"page_num"="\d+"})
     */
    public function indexAction($page_num)
    {
        return $this->render('@DemoWeb/Default/index.html.twig', array(
            'page' => $page_num,
        ));
    }
}
```

**`http://my_project.cn/page/4`**  
**`http://my_project.cn/page`**

```
Hello page 4!
Hello page 1!
```
**配置二**

```
/**
 * @Route("/page")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/index/{page_num}", defaults={"page_num":1}, requirements={"page_num"="\d"})
     */
    public function indexAction($page_num)
    {
        return $this->render('@DemoWeb/Default/index.html.twig', array(
            'page' => $page_num,
        ));
    }
}
```

**路由打印**

```
➜  my_project git:(master) ✗ bin/console debug:router
 -------------------------- -------- -------- ------ ----------------------------------- 
  Name                       Method   Scheme   Host   Path                               
 -------------------------- -------- -------- ------ ----------------------------------- 
  _wdt                       ANY      ANY      ANY    /_wdt/{token}                      
  _profiler_home             ANY      ANY      ANY    /_profiler/                        
  _profiler_search           ANY      ANY      ANY    /_profiler/search                  
  _profiler_search_bar       ANY      ANY      ANY    /_profiler/search_bar              
  _profiler_phpinfo          ANY      ANY      ANY    /_profiler/phpinfo                 
  _profiler_search_results   ANY      ANY      ANY    /_profiler/{token}/search/results  
  _profiler_open_file        ANY      ANY      ANY    /_profiler/open                    
  _profiler                  ANY      ANY      ANY    /_profiler/{token}                 
  _profiler_router           ANY      ANY      ANY    /_profiler/{token}/router          
  _profiler_exception        ANY      ANY      ANY    /_profiler/{token}/exception       
  _profiler_exception_css    ANY      ANY      ANY    /_profiler/{token}/exception.css   
  _twig_error_test           ANY      ANY      ANY    /_error/{code}.{_format}           
  demo_web_default_index     ANY      ANY      ANY    /page/index/{page_num}             
  homepage                   ANY      ANY      ANY    /                                  
 -------------------------- -------- -------- ------ ----------------------------------- 
➜  my_project git:(master) ✗ bin/console router:match /page/index


                                                                                                                        
 [OK] Route "demo_web_default_index" matches                                                                            
                                                                                                                        

+--------------+----------------------------------------------------------+
| Property     | Value                                                    |
+--------------+----------------------------------------------------------+
| Route Name   | demo_web_default_index                                   |
| Path         | /page/index/{page_num}                                   |
| Path Regex   | #^/page/index(?:/(?P<page_num>\d))?$#sD                  |
| Host         | ANY                                                      |
| Host Regex   |                                                          |
| Scheme       | ANY                                                      |
| Method       | ANY                                                      |
| Requirements | page_num: \d                                             |
| Class        | Symfony\Component\Routing\Route                          |
| Defaults     | _controller: DemoWebBundle:Default:index                 |
|              | page_num: 1                                              |
| Options      | compiler_class: Symfony\Component\Routing\RouteCompiler  |
| Callable     | Demo\WebBundle\Controller\DefaultController::indexAction |
+--------------+----------------------------------------------------------+
```

**修改路由名称**

```
/**
 * @Route("/page")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/index/{page_num}", name="page_index", defaults={"page_num":1}, requirements={"page_num"="\d"})
     */
    public function indexAction($page_num)
    {
        return $this->render('@DemoWeb/Default/index.html.twig', array(
            'page' => $page_num,
        ));
    }
}
```

```
➜  my_project git:(master) ✗ bin/console router:match /page/index


                                                                                                                        
 [OK] Route "page_index" matches                                                                                        
                                                                                                                        

+--------------+----------------------------------------------------------+
| Property     | Value                                                    |
+--------------+----------------------------------------------------------+
| Route Name   | page_index                                               |
| Path         | /page/index/{page_num}                                   |
| Path Regex   | #^/page/index(?:/(?P<page_num>\d))?$#sD                  |
| Host         | ANY                                                      |
| Host Regex   |                                                          |
| Scheme       | ANY                                                      |
| Method       | ANY                                                      |
| Requirements | page_num: \d                                             |
| Class        | Symfony\Component\Routing\Route                          |
| Defaults     | _controller: DemoWebBundle:Default:index                 |
|              | page_num: 1                                              |
| Options      | compiler_class: Symfony\Component\Routing\RouteCompiler  |
| Callable     | Demo\WebBundle\Controller\DefaultController::indexAction |
+--------------+----------------------------------------------------------+
```

**获取请求参数**

```
use Symfony\Component\HttpFoundation\Request;
/**
 * @Route("/page")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/index/{page_num}", name="page_index", defaults={"page_num":1}, requirements={"page_num"="\d"})
     */
    public function indexAction(Request $request, $page_num)
    {
        $a = $request->get('a');

        return $this->render('@DemoWeb/Default/index.html.twig', array(
            'page' => $page_num,
            'a' => $a,
        ));
    }
}
```

**`http://my_project.cn/page/index?a=888`**

```
Hello page 1!
a = 888
```

## 控制器

#### 基本概念

* 输入(Request) -- header信息、get信息、post数据
* 输出(Response) -- 页面、JSON字符串、URL

Symfony是对Request进行加工，根据业务需求处理成特定的Response并返回给用户的流程。Request和Response都是Symfony的两个类。

#### Request

相同参数的url，get请求优先于post请求。

#### Response

* 返回Response
* 重定向RedirectResponse
* 返回JsonResponse

#### Session

```
$this->getRequest()->getSession->set("c", 1000);
$this->getRequest()->getSession->get("c");
```
session值取出为空，有可能`app/cache/dev`目录的权限设置不对。

使用在表单验证

```
$this->getRequest()->getSession->getFlashBag()->add(
	'notice',
	'you hace something wrong'
);
```

#### Service

* SOA 面向服务架构

显示所有service

```
➜  my_project git:(master) ✗ bin/console debug:container 

Symfony Container Public Services
=================================

 ---------------------------------------------------------------------------------------------------------- --------------------------------------------------------------------------- 
  Service ID                                                                                                 Class name                                                                 
 ---------------------------------------------------------------------------------------------------------- --------------------------------------------------------------------------- 
  AppBundle\Controller\DefaultController                                                                     AppBundle\Controller\DefaultController                                     
  Symfony\Bundle\FrameworkBundle\Controller\RedirectController                                               Symfony\Bundle\FrameworkBundle\Controller\RedirectController               
  Symfony\Bundle\FrameworkBundle\Controller\TemplateController                                               Symfony\Bundle\FrameworkBundle\Controller\TemplateController               
  abstract.instanceof.AppBundle\Controller\DefaultController                                                 AppBundle\Controller\DefaultController                                     
  cache.app                                                                                                  Symfony\Component\Cache\Adapter\TraceableAdapter                           
  cache.app_clearer                                                                                          alias for "cache.default_clearer"                                          
  cache.global_clearer                                                                                       Symfony\Component\HttpKernel\CacheClearer\Psr6CacheClearer                 
  cache.system                                                                                               Symfony\Component\Cache\Adapter\TraceableAdapter                           
  cache.system_clearer                                                                                       Symfony\Component\HttpKernel\CacheClearer\Psr6CacheClearer                 
  cache_clearer                                                                                              Symfony\Component\HttpKernel\CacheClearer\ChainCacheClearer                
  cache_warmer                                                                                               Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerAggregate              
  console.command.doctrine_bundle_doctrinecachebundle_command_containscommand                                alias for "doctrine_cache.contains_command"                                
  console.command.doctrine_bundle_doctrinecachebundle_command_deletecommand                                  alias for "doctrine_cache.delete_command"                                  
  console.command.doctrine_bundle_doctrinecachebundle_command_flushcommand                                   alias for "doctrine_cache.flush_command"                                   
  console.command.doctrine_bundle_doctrinecachebundle_command_statscommand                                   alias for "doctrine_cache.stats_command"                                   
  console.command_loader                                                                                     Symfony\Component\Console\CommandLoader\ContainerCommandLoader             
  data_collector.dump                                                                                        Symfony\Component\HttpKernel\DataCollector\DumpDataCollector               
  database_connection                                                                                        alias for "doctrine.dbal.default_connection"                               
  doctrine                                                                                                   Doctrine\Bundle\DoctrineBundle\Registry                                    
  doctrine.dbal.default_connection                                                                           Doctrine\DBAL\Connection                                                   
  doctrine.orm.default_entity_manager                                                                        Doctrine\ORM\EntityManager                                                 
  doctrine.orm.default_metadata_cache                                                                        alias for "doctrine_cache.providers.doctrine.orm.default_metadata_cache"   
  doctrine.orm.default_query_cache                                                                           alias for "doctrine_cache.providers.doctrine.orm.default_query_cache"      
  doctrine.orm.default_result_cache                                                                          alias for "doctrine_cache.providers.doctrine.orm.default_result_cache"     
  doctrine.orm.entity_manager                                                                                alias for "doctrine.orm.default_entity_manager"                            
  doctrine_cache.providers.doctrine.orm.default_metadata_cache                                               Doctrine\Common\Cache\ArrayCache                                           
  doctrine_cache.providers.doctrine.orm.default_query_cache                                                  Doctrine\Common\Cache\ArrayCache                                           
  doctrine_cache.providers.doctrine.orm.default_result_cache                                                 Doctrine\Common\Cache\ArrayCache                                           
  event_dispatcher                                                                                           alias for "debug.event_dispatcher"                                         
  filesystem                                                                                                 Symfony\Component\Filesystem\Filesystem                                    
  form.factory                                                                                               Symfony\Component\Form\FormFactory                                         
  form.type.birthday                                                                                         Symfony\Component\Form\Extension\Core\Type\BirthdayType                    
  form.type.button                                                                                           Symfony\Component\Form\Extension\Core\Type\ButtonType                      
  form.type.checkbox                                                                                         Symfony\Component\Form\Extension\Core\Type\CheckboxType                    
  form.type.collection                                                                                       Symfony\Component\Form\Extension\Core\Type\CollectionType                  
  form.type.country                                                                                          Symfony\Component\Form\Extension\Core\Type\CountryType                     
  form.type.currency                                                                                         Symfony\Component\Form\Extension\Core\Type\CurrencyType                    
  form.type.date                                                                                             Symfony\Component\Form\Extension\Core\Type\DateType                        
  form.type.datetime                                                                                         Symfony\Component\Form\Extension\Core\Type\DateTimeType                    
  form.type.email                                                                                            Symfony\Component\Form\Extension\Core\Type\EmailType                       
  form.type.file                                                                                             Symfony\Component\Form\Extension\Core\Type\FileType                        
  form.type.hidden                                                                                           Symfony\Component\Form\Extension\Core\Type\HiddenType                      
  form.type.integer                                                                                          Symfony\Component\Form\Extension\Core\Type\IntegerType                     
  form.type.language                                                                                         Symfony\Component\Form\Extension\Core\Type\LanguageType                    
  form.type.locale                                                                                           Symfony\Component\Form\Extension\Core\Type\LocaleType                      
  form.type.money                                                                                            Symfony\Component\Form\Extension\Core\Type\MoneyType                       
  form.type.number                                                                                           Symfony\Component\Form\Extension\Core\Type\NumberType                      
  form.type.password                                                                                         Symfony\Component\Form\Extension\Core\Type\PasswordType                    
  form.type.percent                                                                                          Symfony\Component\Form\Extension\Core\Type\PercentType                     
  form.type.radio                                                                                            Symfony\Component\Form\Extension\Core\Type\RadioType                       
  form.type.range                                                                                            Symfony\Component\Form\Extension\Core\Type\RangeType                       
  form.type.repeated                                                                                         Symfony\Component\Form\Extension\Core\Type\RepeatedType                    
  form.type.reset                                                                                            Symfony\Component\Form\Extension\Core\Type\ResetType                       
  form.type.search                                                                                           Symfony\Component\Form\Extension\Core\Type\SearchType                      
  form.type.submit                                                                                           Symfony\Component\Form\Extension\Core\Type\SubmitType                      
  form.type.text                                                                                             Symfony\Component\Form\Extension\Core\Type\TextType                        
  form.type.textarea                                                                                         Symfony\Component\Form\Extension\Core\Type\TextareaType                    
  form.type.time                                                                                             Symfony\Component\Form\Extension\Core\Type\TimeType                        
  form.type.timezone                                                                                         Symfony\Component\Form\Extension\Core\Type\TimezoneType                    
  form.type.url                                                                                              Symfony\Component\Form\Extension\Core\Type\UrlType                         
  http_kernel                                                                                                Symfony\Component\HttpKernel\HttpKernel                                    
  instanceof.Symfony\Bundle\FrameworkBundle\Controller\Controller.0.AppBundle\Controller\DefaultController   AppBundle\Controller\DefaultController                                     
  kernel                                                                                                                                                                                
  mailer                                                                                                     alias for "swiftmailer.mailer.default"                                     
  profiler                                                                                                   Symfony\Component\HttpKernel\Profiler\Profiler                             
  request_stack                                                                                              Symfony\Component\HttpFoundation\RequestStack                              
  router                                                                                                     alias for "router.default"                                                 
  routing.loader                                                                                             Symfony\Bundle\FrameworkBundle\Routing\DelegatingLoader                    
  security.authentication_utils                                                                              Symfony\Component\Security\Http\Authentication\AuthenticationUtils         
  security.authorization_checker                                                                             Symfony\Component\Security\Core\Authorization\AuthorizationChecker         
  security.csrf.token_manager                                                                                Symfony\Component\Security\Csrf\CsrfTokenManager                           
  security.password_encoder                                                                                  alias for "security.user_password_encoder.generic"                         
  security.token_storage                                                                                     Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage  
  service_container                                                                                          Symfony\Component\DependencyInjection\ContainerInterface                   
  services_resetter                                                                                          Symfony\Component\HttpKernel\DependencyInjection\ServicesResetter          
  session                                                                                                    Symfony\Component\HttpFoundation\Session\Session                           
  swiftmailer.mailer.abstract                                                                                Swift_Mailer                                                               
  swiftmailer.mailer.default                                                                                 Swift_Mailer                                                               
  swiftmailer.mailer.default.plugin.messagelogger                                                            Swift_Plugins_MessageLogger                                                
  swiftmailer.mailer.default.transport.real                                                                  alias for "swiftmailer.mailer.default.transport.smtp"                      
  translator                                                                                                 Symfony\Component\Translation\IdentityTranslator                           
  twig                                                                                                       Twig\Environment                                                           
  twig.controller.exception                                                                                  Symfony\Bundle\TwigBundle\Controller\ExceptionController                   
  twig.controller.preview_error                                                                              Symfony\Bundle\TwigBundle\Controller\PreviewErrorController                
  validator                                                                                                  alias for "debug.validator"                                                
  var_dumper.cloner                                                                                          Symfony\Component\VarDumper\Cloner\VarCloner                               
  web_profiler.controller.exception                                                                          Symfony\Bundle\WebProfilerBundle\Controller\ExceptionController            
  web_profiler.controller.profiler                                                                           Symfony\Bundle\WebProfilerBundle\Controller\ProfilerController             
  web_profiler.controller.router                                                                             Symfony\Bundle\WebProfilerBundle\Controller\RouterController               
 ---------------------------------------------------------------------------------------------------------- --------------------------------------------------------------------------- 
```

#### 控制器总结

新建一个BaseController，可以将共享的代码写在BaseController中。

## TODO


TODO...
