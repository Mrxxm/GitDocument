## Laravel基础

#### 核心目录文件介绍

* app 包含业务程序的核心代码

* app/http 业务逻辑代码主要编写位置

* bootstrap 包含框架启动和配置的文件

* config 包含所有应用程序的配置文件(缓存、数据库、邮件等)

* database 包含数据库迁移和填充文件

* public 包含项目入口和静态资源、js、css文件

* resources 包含视图和原始的资源文件

* storage 包含编译后的模板文件以及基于session文件的文件缓存、日志

* tests 包含测试代码、单元测试

* vendor 包含依赖文件

#### 入口文件index.php

首先，加载了bootstrap文件夹下的`autoload.php`文件。`autoload.php`文件加载了第三方的`autoload.php`文件。

其次，加载了bootstrap文件夹下的`app.php`文件。`app.php`文件中实例化了`$app`并且返回了`$app`。

拿到`$app`之后，可以完成想完成的任务。

#### 路由

作用：建立url和程序之间映射

请求类型包括：get、post、put、patch、delete

* 基本路由

编写get和post路由

```
Route::get('basic1', function() {
    return 'Hello World';
});

Route:post('basic2', function() {
    return 'Basic2';
});
```

* 多请求路由

match方法指定请求类型，any方法满足所有请求类型

```
Route::match(['get', 'post'], 'multy1', function () {
    return 'multy1';
});

Route::any('multy2', function() {
    return 'multy2';
});
```

* 路由参数

路由的默认传参，正则限制

```
Route::get('user/{id}', function($id) {
    return 'user-id-' . $id;
});

# 默认传参
Route::get('user/{name?}', function($name = 'xxm') {
    return 'user-name-' . $name;
});

# where方法的正则限制
Route::get('user/{name?}', function($name = 'xxm') {
    return 'user-name-' . $name;
})->where('name', '[A-Za-z]+');

# where方法多参数正则限制
Route::get('user/{id}/{name?}', function($id, $name = 'xxm') {
    return 'user-id-' . $id . 'name-' . $name;
})->where(
    ['id' => '[0-9]', 
    'name' => '[A-Za-z]+']
);
```

* 路由别名

作用：可以用别名生成对应的url

```
Route::get('user/member-center', ['as' => 'center', function() {
    return route('center');
}]);
```

* 路由群组

添加路由前缀实现路由群组

```
Route::group(['prefix' => 'member'], function() {

    Route::any('multy2', function()
    {
        return 'member-multy2';
    });
    
});
```

* 路由中输出视图

```
Route::get('view', function() {
    return view('welcome');
});
```

#### 控制器

路由关联控制器,并起别名

```
Route::get('/home', 'HomeController@index');

Route::get('/home', [
'uses' => 'HomeController@index',
'as' => 'home']);
```

#### 视图

```
public function showPicture() 
{
    $picInfo = DB::select('SELECT * FROM `pictures` WHERE 1');


    return view('web.show', array(
        'pictures' => $picInfo
    ));
}
```

#### 模型

#### 数据库操作

`DB facade`、查询构造器、`Eloquent ORM`三种操作数据库的方式

* 新建数据表

* 连接数据库(config/database.php和.env)

`DB facade`

```
$picInfo = DB::select('SELECT * FROM `pictures` WHERE 1');
```

#### 查询构造器

* 使用PDO参数绑定，以保护应用程序免于SQL注入因此传入的参数不需要额外的转义特殊字符

查询构造器

```
 $users = DB::table('users')->paginate(10);

$count = DB::table('users')->count();
```

#### 查询构造器-新增并返回id值、插入多条数据

```
DB::table('student')->insertGetId(
['name' => 'xxm', 'age' => 23]
);

# 返回值为布尔值
DB::table('student')->insertGetId(
['name' => 'xxm', 'age' => 23],
[],
···,
);
```

#### 查询构造器-更新操作

```
# 返回影响的行数
DB::table('sutdent')->where('id', 12)->update(['age' => 12]);

# 返回影响的行数，默认自增一，
# 自减decrement();
DB::table('student')->increment('age', 3);

# 自增的同时修改其他字段
DB::table('student')->where('id', 12)->increment('age', 3, ['name' => 'iimooc']);
```

#### 查询构造器-删除操作

```
# 删除整个表
DB::table('student')->delete();

# 条件删除 id大于等于10的数据 返回值为受影响的行数
DB:table('student')->where('id', '>=', '10')->delete();

# 整表删除 不返回任何数据
truncate();
```

#### 查询构造器-查询数据

* get()

```
# 获取表的所有数据
DB::table('student')->get();
```

* first()

```
# 返回一条记录
DB::table('student')->orderBy('id', 'desc')->first();
```

* where()

```
# 单个条件
DB::table('student')->where('id', '>', '10')->get();

# 多个条件
DB::table('student')->whereRaw('id > ? and age > ?', [10, 18])->get();
```

* pluck()

```
# 返回结果集中指定字段
DB::table('student')
->pluck(‘name’);
```

* lists()

```
# 返回结果集中指定字段并且指定某个键作为下标
# id作为下标
DB::table('student')
->lists(‘name’, 'id');
```

* select()

```
# 指定查找
DB::table('student')
->select(‘id’, 'name', 'age')
->get();
```

* chunk()

```
# chunk循环查询数据 按条数
DB::table('student')
->chunk(2, function($students) {
    
});
```

#### 查询构造器-聚合函数

* count()

```
# 返回表的记录数
DB::table('student')
->count();
```

* max()

```
# 返回最大值
DB::table('student')
->max('age');
```

* min()

```
# 与max同理
DB::table('student')
->min(‘age’);
```

* avg()

```
# 平均数
DB::table('student')
->avg('age');
```

* sum()

```
# 某一列的和
DB::table('student')
->sum('age');
```

#### Eloquent ORM

* 查询（all()、find()、findOrFail()）

* 新增数据、自定义时间戳和批量赋值

* 修改数据

* 删除数据

指定表名、id

```
class Student extends Model
{
    protected $table = 'student';
    
    protected $primaryKey = 'id';
    
    // 指定允许批量赋值的字段
    protected $fillable = ['name', 'age'];
    
    // 指定不允许批量赋值的字段
    protected $guarded = [];
    
    // 自动维护时间戳
    protected $timestamps = false; 
    
    // 指定存储时间为时间戳
    protected function getDateFormat(){
        return time();
    }
    
    // 返回数据库中原始的时间戳
    protected function asDateTime($val){
        return $val;
    }
} 
```

#### Eloquent ORM - 查询数据

```
# 返回的是一个集合Collection，内容在attributes属性中
Student::all();

# 根据主键进行查询
Student::find(1);

# 根据主键查询，无结果则抛出异常
Student::findOrFail(1);
```

模型配合查询构造器使用

```
# 返回也是Collection集合
Student::where('id', '>', 10)
->orderBy('id', 'desc')
->first();

# chunk循环查询数据 按条数
Student::chunk(2, function($student) {
    var_dump($student);
});
```

#### Eloquent ORM - 新增数据

Create方法需要和$fillable $guarded 属性配置使用，所谓的批量赋值指的是多个字段一同插入数据库的操作

```
# 返回值为布尔值
$student = new Student();
$student->name = 'xxm';
$student->age = 19;
$student->save();

# 使用模型的Create方法新增数据 需要指定批量赋值的字段 $fillable $guarded 属性类似过滤
Student::create([
    'name' => 'xxm',
    'age' => 10
]);

# firstOrCreate() 以属性查找用户 若没有则新增 并取得新的实例
Student::firstOrCreate([
    'name' => 'xxm'
]);

# firstOrNew() 以属性查找用户 若没有则建立新的实例 若需要保存则调用save方法
# 返回为布尔值
Student::firstOrNew([
    'name' => 'xxm'
])->save();
```

#### Eloquent ORM - 修改数据

* 模型更新

```
$student = Student::find(10);
$student->name = 'kitty';
$student->save();
```

* 批量更新

```
# 返回影响的行数
Student::where('id', '>', '10')->update(['age' => '20']);
```

#### Eloquent ORM - 删除数据

* 模型删除

```
# 返回布尔值
$student = Student::find(10);
$student->delete();
```

* 主键删除

```
# 返回值为影响的行数
Student::destroy(10);

Student::destroy(10, 11, 12);

Student::destroy([10, 12]);
```

* 指定条件删除

```
# 返回值为影响的行数
Student::where('id', '>', '10')->delete();
```






