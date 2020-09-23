# TP6 路由部分源码分析

## 路由规则预处理

#### Step1: 开始

从一个路由实例开始分析：

```
Route::get('hello2/[:name]', 'index/hello2');
```

* Route类 get方法

```
/**
 * 注册GET路由
 * @access public
 * @param string $rule  路由规则
 * @param mixed  $route 路由地址
 * @return RuleItem
 */
public function get(string $rule, $route): RuleItem
{
    return $this->rule($rule, $route, 'GET');
}
```

* Route类 rule方法

```
/**
 * 注册路由规则
 * @access public
 * @param string $rule   路由规则
 * @param mixed  $route  路由地址
 * @param string $method 请求类型
 * @return RuleItem
 */
public function rule(string $rule, $route = null, string $method = '*'): RuleItem
{
    if ($route instanceof Response) {
        // 兼容之前的路由到响应对象，感觉不需要，使用场景很少，闭包就能实现
        $route = function () use ($route) {
            return $route;
        };
    }
    return $this->group->addRule($rule, $route, $method);
}
``` 

#### Step2:`$this->group`实例化过程分析

* Route类 构造方法

```
public function __construct(App $app)
{
    $this->app      = $app;
    $this->ruleName = new RuleName();
    $this->setDefaultDomain();
    
    if (is_file($this->app->getRuntimePath() . 'route.php')) {
        // 读取路由映射文件
        $this->import(include $this->app->getRuntimePath() . 'route.php');
    }
}
```

* Route setDefaultDomain方法(`$this->group = $domain`)

```
/**
 * 初始化默认域名
 * @access protected
 * @return void
 */
protected function setDefaultDomain(): void
{
    // 注册默认域名
    $domain = new Domain($this);


    $this->domains['-'] = $domain;


    // 默认分组
    $this->group = $domain;
}

```

* Domain类继承RuleGroup类

```
/**
 * 域名路由
 */
class Domain extends RuleGroup
{}
```

* 所以可以在Route中通过`$this->group->addRule()`调用addRule方法

```
/**
 * 注册路由规则
 * @access public
 * @param string $rule   路由规则
 * @param mixed  $route  路由地址
 * @param string $method 请求类型
 * @return RuleItem
 */
public function rule(string $rule, $route = null, string $method = '*'): RuleItem
{
    if ($route instanceof Response) {
        // 兼容之前的路由到响应对象，感觉不需要，使用场景很少，闭包就能实现
        $route = function () use ($route) {
            return $route;
        };
    }
    return $this->group->addRule($rule, $route, $method);
}
```

#### Step3: RuleGroup类 addRule方法

```
/**
 * 添加分组下的路由规则
 * @access public
 * @param  string $rule   路由规则
 * @param  mixed  $route  路由地址
 * @param  string $method 请求类型
 * @return RuleItem
 */
public function addRule(string $rule, $route = null, string $method = '*'): RuleItem
{
    // 读取路由标识
    if (is_string($route)) {
        $name = $route;
    } else {
        $name = null;
    }


    $method = strtolower($method);

    // 路由为空或者跟路由 会被转化成 /$或$
    if ('' === $rule || '/' === $rule) {
        $rule .= '$’; 
    }


    // 创建路由规则实例
    $ruleItem = new RuleItem($this->router, $this, $name, $rule, $route, $method);


    $this->addRuleItem($ruleItem, $method);


    return $ruleItem;
}
```

* `var_dump($ruleItem)`

```
object(think\route\RuleItem)#35 (7) {
  ["name"]=>
  string(12) "index/hello2"
  ["rule"]=>
  string(14) "hello2/<name?>"
  ["route"]=>
  string(12) "index/hello2"
  ["method"]=>
  string(3) "get"
  ["vars"]=>
  array(0) {
  }
  ["option"]=>
  array(0) {
  }
  ["pattern"]=>
  array(0) {
  }
}
```


#### Step4:RuleItem类

* 构造方法

```
/**
 * 架构函数
 * @access public
 * @param  Route             $router 路由实例
 * @param  RuleGroup         $parent 上级对象
 * @param  string            $name 路由标识
 * @param  string            $rule 路由规则
 * @param  string|\Closure   $route 路由地址
 * @param  string            $method 请求类型
 */
public function __construct(Route $router, RuleGroup $parent, string $name = null, string $rule = '', $route = null, string $method = '*')
{
    $this->router = $router;
    $this->parent = $parent;
    $this->name   = $name;
    $this->route  = $route;
    $this->method = $method;


    $this->setRule($rule);


    $this->router->setRule($this->rule, $this);
}

```

* setRule(判断路由最后一个字符是不是$符，有则是完全匹配)

```
/**
 * 路由规则预处理
 * @access public
 * @param  string      $rule     路由规则
 * @return void
 */
public function setRule(string $rule): void
{
    if ('$' == substr($rule, -1, 1)) {
        // 是否完整匹配
        $rule = substr($rule, 0, -1);

        // 设置完全匹配为true    
        $this->option['complete_match'] = true;
    }

    // 转化 /hello/:name  => hello/:name
    $rule = '/' != $rule ? ltrim($rule, '/') : '';


    if ($this->parent && $prefix = $this->parent->getFullName()) {
        $rule = $prefix . ($rule ? '/' . ltrim($rule, '/') : '');
    }


    if (false !== strpos($rule, ':')) {
        $this->rule = preg_replace(['/\[\:(\w+)\]/', '/\:(\w+)/'], ['<\1?>', '<\1>'], $rule);
    } else {
        $this->rule = $rule;
    }


    // 生成路由标识的快捷访问
    $this->setRuleName();
}
```

## 生成路由标识的快捷访问

#### Step1:`RuleItem -> __construct -> setRule -> setRuleName`

* setRuleName方法

```
/**
 * 设置路由标识 用于URL反解生成
 * @access protected
 * @param  bool $first 是否插入开头
 * @return void
 */
protected function setRuleName(bool $first = false): void
{
    if ($this->name) {
        $this->router->setName($this->name, $this, $first);
    }
}
```

* Route类 setName方法

```
/**
 * 注册路由标识
 * @access public
 * @param string   $name     路由标识
 * @param RuleItem $ruleItem 路由规则
 * @param bool     $first    是否优先
 * @return void
 */
public function setName(string $name, RuleItem $ruleItem, bool $first = false): void
{
    $this->ruleName->setName($name, $ruleItem, $first);
}
```

* RuleName类 setName方法

```
/**
 * 注册路由标识
 * @access public
 * @param  string   $name  路由标识
 * @param  RuleItem $ruleItem 路由规则
 * @param  bool     $first 是否优先
 * @return void
 */
public function setName(string $name, RuleItem $ruleItem, bool $first = false): void
{
    $name = strtolower($name);
    if ($first && isset($this->item[$name])) {
        array_unshift($this->item[$name], $ruleItem);
    } else {
        $this->item[$name][] = $ruleItem;
    }
}
```

#### Step2:RuleItem类构造方法`var_dump($this->router)`


```
 public function __construct(Route $router, RuleGroup $parent, string $name = null, string $rule = '', $route = null, string $method = '*')
    {
        $this->router = $router;
        $this->parent = $parent;
        $this->name   = $name;
        $this->route  = $route;
        $this->method = $method;

        $this->setRule($rule);

        $this->router->setRule($this->rule, $this);
        dd($this->router);
    }
```

* 打印

```
^ think\Route {#36 ▼
  #rest: array:7 [▶]
  #config: array:21 [▶]
  #app: think\App {#3 ▶}
  #request: app\Request {#7 ▶}
  #ruleName: think\route\RuleName {#32 ▼
    #item: array:1 [▼
      "index/hello2" => array:1 [▼
        0 => think\route\RuleItem {#35 ▶}
      ]
    ]
    #rule: array:1 [▼
      "hello2/<name?>" => array:1 [▼
        "index/hello2" => think\route\RuleItem {#35 ▶}
      ]
    ]
    #group: []
  }
  #host: "127.0.0.1"
  #group: think\route\Domain {#38 ▶}
  #bind: []
  #domains: array:1 [▶]
  #cross: null
  #lazy: false
  #isTest: false
  #mergeRuleRegex: false
  #removeSlash: false
}
```

## 路由参数和变量规则

#### Step1:路由参数

```
Route::get('/hello/:name', 'index/hello')->ext('html');
```

访问方式：`http://127.0.0.1/hello/1.html`

* Route类 ext方法

```
/**
 * 检查后缀
 * @access public
 * @param  string $ext URL后缀
 * @return $this
 */
public function ext(string $ext = '')
{
    return $this->setOption('ext', $ext);
}
```

#### Step2:变量规则

```
Route::pattern([
    ‘id’ => ‘\d+'
]);
```

* Route类 pattern方法

```
/**
 * 注册变量规则
 * @access public
 * @param  array $pattern 变量规则
 * @return $this
 */
public function pattern(array $pattern)
{
    $this->pattern = array_merge($this->pattern, $pattern);


    return $this;
}
```

## 资源路由

#### Step1:介绍

[文档：](https://www.kancloud.cn/manual/thinkphp6_0/1037501)

支持设置RESTFUL请求的资源路由

```
Route::resource(‘blog’, ‘index/blog’);
```

![](https://img9.doubanio.com/view/photo/l/public/p2618989366.jpg)


## 设置三种不同路由分析源码

#### Step1:设置路由

```
// 路由章节测试路由
Route::resource('blog', 'res'); // 资源路由
Route::get('/hello/:name', 'index/hello')->ext('html')->pattern(['name' => '\d+']);
Route::get('/hello2/[:name]', 'index/hello2'); // name参数可选
```

## dispatch认识

#### Step1:项目入口文件

* public/index.php

```
<?php

// [ 应用入口文件 ]
namespace think;

require __DIR__ . '/../vendor/autoload.php';

// 执行HTTP应用并响应
$http = (new App())->http;

$response = $http->run();

$response->send();

$http->end($response);
```

#### Step2：run方法开始

* `Http run -> Http runWithRequest -> Http dispatchToRoute -> Route dispatch`

```
/**
 * 路由调度
 * @param Request $request
 * @param Closure|bool $withRoute
 * @return Response
 */
public function dispatch(Request $request, $withRoute = true)
{
    $this->request = $request;
    $this->host    = $this->request->host(true);
    $this->init();


    if ($withRoute) {
        //加载路由
        if ($withRoute instanceof Closure) {
            $withRoute();
        }
        $dispatch = $this->check();
    } else {
        $dispatch = $this->url($this->path());
    }
    
    $dispatch->init($this->app);


    return $this->app->middleware->pipeline('route')
        ->send($request)
        ->then(function () use ($dispatch) {
            return $dispatch->run();
        });
}
```

* 打印 Route类 1.`$this->rest` 2.`$this->config` 3.`init之前的$dispatch ` 4.`init之后的$dispatch`

1.`$this->rest`

```
^ array:7 [▼
  "index" => array:3 [▼
    0 => "get"
    1 => ""
    2 => "index"
  ]
  "create" => array:3 [▼
    0 => "get"
    1 => "/create"
    2 => "create"
  ]
  "edit" => array:3 [▼
    0 => "get"
    1 => "/<id>/edit"
    2 => "edit"
  ]
  "read" => array:3 [▼
    0 => "get"
    1 => "/<id>"
    2 => "read"
  ]
  "save" => array:3 [▼
    0 => "post"
    1 => ""
    2 => "save"
  ]
  "update" => array:3 [▼
    0 => "put"
    1 => "/<id>"
    2 => "update"
  ]
  "delete" => array:3 [▼
    0 => "delete"
    1 => "/<id>"
    2 => "delete"
  ]
]
```

2.`$this->config` 

```
^ array:16 [▼
  "pathinfo_depr" => "/"
  "url_lazy_route" => false
  "url_route_must" => false
  "route_rule_merge" => false
  "route_complete_match" => false
  "remove_slash" => false
  "route_annotation" => false
  "default_route_pattern" => "[\w\.]+"
  "url_html_suffix" => "html"
  "controller_layer" => "controller"
  "empty_controller" => "Error"
  "controller_suffix" => false
  "default_controller" => "Index"
  "default_action" => "index"
  "action_suffix" => ""
  "url_common_param" => true
]
```

3.`init之前的$dispatch `

```
^ think\route\dispatch\Controller {#40 ▼
  #controller: null
  #actionName: null
  #app: null
  #request: app\Request {#7 ▶}
  #rule: think\route\RuleItem {#39 ▶}
  #dispatch: array:2 [▼
    0 => "index"
    1 => "hello2"
  ]
  #param: []
  dispatch: array:2 [▶]
  param: []
  rule: think\route\RuleItem {#39 ▶}
}
```

4.`init之后的$dispatch`

```
^ think\route\dispatch\Controller {#40 ▼
  #controller: "Index"
  #actionName: "hello2"
  #app: think\App {#3 ▶}
  #request: app\Request {#7 ▶}
  #rule: think\route\RuleItem {#39 ▶}
  #dispatch: array:2 [▼
    0 => "index"
    1 => "hello2"
  ]
  #param: []
  dispatch: array:2 [▶]
  param: []
  rule: think\route\RuleItem {#39 ▶}
}
```


## Route类 check方法

#### Step1

```
/**
 * 检测URL路由
 * @access public
 * @return Dispatch|false
 * @throws RouteNotFoundException
 */
public function check()
{
    // 自动检测域名路由
    $url = str_replace($this->config['pathinfo_depr'], '|', $this->path()); // hello|1

    // 完全匹配
    $completeMatch = $this->config['route_complete_match'];

    
    $result = $this->checkDomain()->check($this->request, $url, $completeMatch);


    if (false === $result && !empty($this->cross)) {
        // 检测跨域路由
        $result = $this->cross->check($this->request, $url, $completeMatch);
    }


    if (false !== $result) {
        return $result;
    } elseif ($this->config['url_route_must']) {
        throw new RouteNotFoundException();
    }


    return $this->url($url);
}
```

* 打印：`$result = $this->checkDomain()->check($this->request, $url, $completeMatch);`

返回：1.配置路由的是init之前的$dispatch变量 2.未配置路由的是false

* Route类 url方法(未配置路由访问该方法)

```
/**
 * 默认URL解析
 * @access public
 * @param string $url URL地址
 * @return Dispatch
 */
public function url(string $url): Dispatch
{
    if ($this->request->method() == 'OPTIONS') {
        // 自动响应options请求
        return new Callback($this->request, $this->group, function () {
            return Response::create('', 'html', 204)->header(['Allow' => 'GET, POST, PUT, DELETE']);
        });
    }


    return new UrlDispatch($this->request, $this->group, $url);
}

```

* 打印：1.`$url` 2.`$this->group` 3.`new UrlDispatch()`

1.`$url` 


```
index|index|test
```

2.`$this->group` 

```
think\route\Domain对象
```

3.`new UrlDispatch()`

```
 think\route\dispatch\Url {#40 ▼
  #controller: null
  #actionName: null
  #app: null
  #request: app\Request {#7 ▶}
  #rule: think\route\Domain {#38 ▶}
  #dispatch: array:2 [▼
    0 => "hel"
    1 => "index"
  ]
  #param: []
  dispatch: array:2 [▶]
  param: []
  rule: think\route\Domain {#38 ▶}
}
```

* Url类 构造方法

```
/**
 * Url Dispatcher
 */
class Url extends Controller
{

    public function __construct(Request $request, Rule $rule, $dispatch)
    {
        $this->request = $request;
        $this->rule    = $rule;
        // 解析默认的URL规则
        $dispatch = $this->parseUrl($dispatch);


        parent::__construct($request, $rule, $dispatch, $this->param);
    }
```

## Domain类 check方法

#### Step1:路由回顾

```
// 路由章节测试路由
Route::resource('blog', 'res'); // 资源路由
Route::get('/hello/:name', 'index/hello')->ext('html')->pattern(['name' => '\d+']);
Route::get('/hello2/[:name]', 'index/hello2'); // name参数可选
```

#### Step2:打印相关

* 打印：1.`$this->rules` 2.`$this->option`

`think\route\Domain`对象

1.`$this->rules`(get路由会有两条规则get和option)

```
^ array:5 [▼
  0 => array:2 [▼
    0 => "*"
    1 => think\route\Resource {#35 ▼
      #resource: "blog"
      #route: "res"
      #rest: array:7 [▶]
      #model: []
      #validate: []
      #middleware: []
      #rules: []
      #rule: null
      #miss: null
      #fullName: "blog"
      #alias: null
      #name: "blog"
      #domain: "-"
      #router: think\Route {#36 ▶}
      #parent: think\route\Domain {#38 ▶}
      #method: null
      #vars: []
      #option: array:1 [▶]
      #pattern: []
      #mergeOptions: array:3 [▶]
      name: "blog"
      rule: null
      route: "res"
      method: null
      vars: []
      option: array:1 [▶]
      pattern: []
    }
  ]
  1 => array:2 [▼
    0 => "get"
    1 => think\route\RuleItem {#28 ▼
      #miss: false
      #autoOption: true
      #name: "index/hello"
      #domain: null
      #router: think\Route {#36}
      #parent: think\route\Domain {#38 ▶}
      #rule: "hello/<name>"
      #route: "index/hello"
      #method: "get"
      #vars: []
      #option: array:1 [▶]
      #pattern: array:1 [▶]
      #mergeOptions: array:3 [▶]
      name: "index/hello"
      rule: "hello/<name>"
      route: "index/hello"
      method: "get"
      vars: []
      option: array:1 [▶]
      pattern: array:1 [▶]
    }
  ]
  2 => array:2 [▼
    0 => "options"
    1 => think\route\RuleItem {#28 ▶}
  ]
  3 => array:2 [▶]
  4 => array:2 [▶]
]
```

2.`$this->option`

```
^ array:1 [▼
  "remove_slash" => false
]
```

#### Step2: Domain类 check方法

```
/**
 * 检测域名路由
 * @access public
 * @param  Request      $request  请求对象
 * @param  string       $url      访问地址
 * @param  bool         $completeMatch   路由是否完全匹配
 * @return Dispatch|false
 */
public function check(Request $request, string $url, bool $completeMatch = false)
{
    // 检测URL绑定
    $result = $this->checkUrlBind($request, $url);


    if (!empty($this->option['append'])) {
        $request->setRoute($this->option['append']);
        unset($this->option['append']);
    }


    if (false !== $result) {
        return $result;
    }


    return parent::check($request, $url, $completeMatch);
}

```

#### Step3:调用父类RuleGroup类check方法

```
/**
 * 检测分组路由
 * @access public
 * @param  Request $request       请求对象
 * @param  string  $url           访问地址
 * @param  bool    $completeMatch 路由是否完全匹配
 * @return Dispatch|false
 */
public function check(Request $request, string $url, bool $completeMatch = false)
{
    // 检查分组有效性(路由参数检查)
    if (!$this->checkOption($this->option, $request) || !$this->checkUrl($url)) {
        return false;
    }


    // 解析分组路由
    if ($this instanceof Resource) {
        $this->buildResourceRule();
    } else {
        $this->parseGroupRule($this->rule);
    }


    // 获取当前路由规则
    $method = strtolower($request->method());
    $rules  = $this->getRules($method);


    if ($this->parent) {
        // 合并分组参数
        $this->mergeGroupOptions();
        // 合并分组变量规则
        $this->pattern = array_merge($this->parent->getPattern(), $this->pattern);
    }


    if (isset($this->option['complete_match'])) {
        $completeMatch = $this->option['complete_match'];
    }


    if (!empty($this->option['merge_rule_regex'])) {
        // 合并路由正则规则进行路由匹配检查
        $result = $this->checkMergeRuleRegex($request, $rules, $url, $completeMatch);


        if (false !== $result) {
            return $result;
        }
    }


    // 检查分组路由
    foreach ($rules as $key => $item) {
        // 调用resource下的check方法 调用ruleItem下的check方法
        $result = $item[1]->check($request, $url, $completeMatch);

        if (false !== $result) {
            return $result;
        }
    }


    if ($this->miss && in_array($this->miss->getMethod(), ['*', $method])) {
        // 未匹配所有路由的路由规则处理
        $result = $this->parseRule($request, '', $this->miss->getRoute(), $url, $this->miss->mergeGroupOptions());
    } else {
        $result = false;
    }


    return $result;
}
```

## 重点分析：RuleGroup类check方法

#### Step1:重点内容提取

```
// 获取当前路由规则
$method = strtolower($request->method());
$rules  = $this->getRules($method);

···

// 检查分组路由
foreach ($rules as $key => $item) {

    // 调用resource下的check方法 调用ruleItem下的check方法
    $result = $item[1]->check($request, $url, $completeMatch);

    if (false !== $result) {
        return $result;
    }
}
```

#### Step2:内容回顾

* 路由回顾

```
// 路由章节测试路由
Route::resource('blog', 'res'); // 资源路由
Route::get('/hello/:name', 'index/hello')->ext('html')->pattern(['name' => '\d+']);
Route::get('/hello2/[:name]', 'index/hello2'); // name参数可选
```

* `$this->rules`打印

```
^ array:5 [▼
  0 => array:2 [▼
    0 => "*"
    1 => think\route\Resource {#35 ▼
      #resource: "blog"
      #route: "res"
      #rest: array:7 [▶]
      #model: []
      #validate: []
      #middleware: []
      #rules: []
      #rule: null
      #miss: null
      #fullName: "blog"
      #alias: null
      #name: "blog"
      #domain: "-"
      #router: think\Route {#36 ▶}
      #parent: think\route\Domain {#38 ▶}
      #method: null
      #vars: []
      #option: array:1 [▶]
      #pattern: []
      #mergeOptions: array:3 [▶]
      name: "blog"
      rule: null
      route: "res"
      method: null
      vars: []
      option: array:1 [▶]
      pattern: []
    }
  ]
  1 => array:2 [▼
    0 => "get"
    1 => think\route\RuleItem {#28 ▼
      #miss: false
      #autoOption: true
      #name: "index/hello"
      #domain: null
      #router: think\Route {#36}
      #parent: think\route\Domain {#38 ▶}
      #rule: "hello/<name>"
      #route: "index/hello"
      #method: "get"
      #vars: []
      #option: array:1 [▶]
      #pattern: array:1 [▶]
      #mergeOptions: array:3 [▶]
      name: "index/hello"
      rule: "hello/<name>"
      route: "index/hello"
      method: "get"
      vars: []
      option: array:1 [▶]
      pattern: array:1 [▶]
    }
  ]
  2 => array:2 [▼
    0 => "options"
    1 => think\route\RuleItem {#28 ▶}
  ]
  3 => array:2 [▶]
  4 => array:2 [▶]
]

```

#### Step3:打印

* 1.`$method` 2.`$rules  = $this->getRules($method);`

1.`$method`(为当前调用路由方式，如‘get’)

2.`$rules  = $this->getRules($method);`

```
^ array:3 [▼
  0 => array:2 [▼
    0 => "*"
    1 => think\route\Resource {#35 ▶}
  ]
  1 => array:2 [▼
    0 => "get"
    1 => think\route\RuleItem {#28 ▶}
  ]
  3 => array:2 [▼
    0 => "get"
    1 => think\route\RuleItem {#39 ▶}
  ]
]
```

* 分析

`$this->getRules($method);`

```
/**
 * 获取分组的路由规则
 * @access public
 * @param  string $method 请求类型
 * @return array
 */
public function getRules(string $method = ''): array
{
    if ('' === $method) {
        return $this->rules;
    }


    return array_filter($this->rules, function ($item) use ($method) {
        return $method == $item[0] || $item[0] == '*';
    });
}

```

```
1.RuleGroup getRules (根据$method获取Domain实例中rules属性中对应的路由 + 资源路由)
2.这里请求的路由是hello2 $method = ‘get’ 所以得到了上图打印值
```

#### Step4:`$result = $item[1]->check($request, $url, $completeMatch);`分析

* 这里`$item[1]`包含多个场景对象：1.`Resource` 2.`RuleItem`

#### Step4.1 分析：`Resource`

Resource 继承 RuleGroup 且没有check方法 会重复调用当前check方法 以下是调试内容

* RuleGroup类 部分check方法

```
public function check(Request $request, string $url, bool $completeMatch = false)
{
    echo "1\n";
    var_dump($this->option, $url);
    echo "\n";
    var_dump(!$this->checkOption($this->option, $request), !$this->checkUrl($url));
    echo "\n";
    // 检查分组有效性
    if (!$this->checkOption($this->option, $request) || !$this->checkUrl($url)) {
        return false;
    }
    var_dump(2);
    echo "\n";


    // 解析分组路由
    if ($this instanceof Resource) {
        $this->buildResourceRule();
    } else {
        $this->parseGroupRule($this->rule);
    }
```

* RuleGroup checkRule方法

```
protected function checkUrl(string $url): bool
    {
        var_dump("RuleGroup fullName: " . (is_null($this->fullName) ? 'null' : $this->fullName));
        echo "\n";
        if ($this->fullName) {
            $pos = strpos($this->fullName, '<');


            if (false !== $pos) {
                $str = substr($this->fullName, 0, $pos);
            } else {
                $str = $this->fullName;
            }


//            var_dump(stripos($url, $str));
            if ($str && 0 !== stripos(str_replace('|', '/', $url), $str)) {
                return false;
            }
        }


        return true;
    }

```

* 输出结果

![](https://img1.doubanio.com/view/photo/l/public/p2618992287.jpg)

* 分析

```
前置说明当前路由是'hello2'，大前提是在"检查分组路由"foreach $rules中:

1.输出了两个1说明已经重复调用了，但是未通过$this->checkUrl($url)验证;

2.fullName等于空，是因为第一次调用是Domain对象实例;

3.第二次输出一个false，一个true，说明rules里面的第一个资源路由调用失败验证不通过 return false。

当请求路由为资源路由'blog'时:

3.第二次输出两个false，说明rules里面的第一个资源路由调用成功 继续往下执行。
```

* RuleGroup checkRule方法 内层分析

```
主要判断是stripos(str_replace('|', '/', $url), $str) => stripos($url, $str):

在当前url查找资源路由blog:

1.当路由为hello2时，是未查询到 0 !== false ; return false;外层中加了!号，所以打印为true。

2.当路由为blog时，是查询到 0 !== 0; return true; 外层中加了!号，所以打印为false。
```


