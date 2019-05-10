#### 使用 DB 类操作数据库

* 使用原生SQL语句

* 查询构造器

* `Eloquent ORM`

#### 原生SQL

* 使用`DB::select() DB::insert() DB::update() DB::delete()`

* 在SQL语句中使用占位符绑定传递参数

* `update()`和`delete()`返回影响行数

#### 查询构造器之插入数据

* 语法`DB::table()`指定数据库

* 使用`where()`加入条件

* 使用`get()`或`first()`取多条或一条数据

* `count() max() min() avg() sum()`统计数据

* 使用`exists()` 和 `doesntExist()` 判断数据是否存在

* 使用`DB::raw()`加入原生SQL语句

* 使用`join()`进行关联表查询

* `orderby()`传递 字段 和 排序方式 进行排序

* `groupBy()` 和 `having()`对查询结果进行分组

例

join

```
DB::table('test')
->join('test_extra', 'test.id', '=', 'test_extra.test_id')
->select([
    'test.*',
    'test_extra.description'
])
->get();
```

#### 查询构造器之增删改

* 使用`insert()`插入一条或多条数据

* 传递数组即为批量插入

* 使用`insertGetId()`获取自增主键的ID值

* 使用`update()`更新指定数据

* `increment()` 和 `decrement()` 自增、自减 字段的值

* 使用`delete()`删除指定数据

* 使用`truncate()`清空表

#### ORM 简介

#### 创建 ORM 模型

* 使用`php artisan make:model ModelName` 命令

* 加入`--migration`参数生成对应的数据迁移文件

**ORM中的属性**

* 私有属性`$table`指定表名名称

```
protected $table = 'books';
```

* 私有属性`$timestamps`是否自动维护时间戳

```
protected $timestamps = true;
```

* 常量`CREATE_AT`和`UPDATE_AT`指定时间戳对应的字段

```
const CREATE_AT = 'create_at';
```

#### 模型关系的定义和查询(一对一)

* 使用`hasOne()` 和 `belongsTo()` 定义关联关系

* 参数分别是 模型名 字表外键名 和 主表外键名

一本书中有一张借阅卡(`bookCard`表中定义外键`book_id`)

```
// Book 模型
public function bookCard()
{
    return $this->hasOne('APP\Models\BookCard');
}
```

一个借阅卡只属于一本书

```
// BookCard 模型
public function book()
{
    return $this->belongsTo('APP\Models\Book');
}
```

例1 插入数据

```
$book = Book::find(1);
$book->bookCard()->create([
    'number' => str_random(32)
]);
```

例2 查询数据

```
$res = Book::with('bookCard')->find(1);
dd($res->toArray());
```

#### ORM 模型关系的定义和查询(一对多)

* 使用`hasMany()`和`belongsTo()`定义关联关系

一张图书借阅卡上有多条借阅记录(`BookBorrowHistory`表中定义外键`book_card_id`)

```
// bookCard模型
public function BookBorrowHistory()
{
    return $this->hasMany(BookBorrowHistory::class);
}
```

一条借阅记录只属于一张借阅卡

```
// BookBorrowHistory模型
public function bookCard()
{
    return $this->belongsTo(BookCard::class);
}
```

例1 查询数据

和`with()`方法相同的查询(`with()`方法使用`join`连表查询一条`sql`语句，而以下这种方法使用的是两条`sql`语句)

```
$book_card = BookCard::find(1);
$book_Borrow_history = $book_card->BookBorrowHistory;
dd($book_Borrow_history);
```

例2 查询数据

已知字表数据查询主表数据

```
$book_Borrow_history = BookBorrowHistory::find(1);
$book_card = $book_Borrow_history->bookCard;
dd($book_card);
```

#### ORM 模型关系的定义和查询(多对多)

![](https://img3.doubanio.com/view/photo/l/public/p2555571340.jpg)

![](https://img3.doubanio.com/view/photo/l/public/p2555571343.jpg)

例1 

插入数据 为中间表插入数据 书本1 对应标签2、3、4

```
$book = Book::find(1);
$book->tag()->attach([
    '2', '3', '4'
]);
```

例2 

删除数据 为中间表删除数据 书本1 对应的标签4

```
$book = Book::find(1);
$book->tag()->detach([
   '4'
]);
```

#### 本地作用域和全局作用域

* 定义一个公用的查询方法 以便复用多次

* 在需要复用的查询方法前加入`scope`关键字

![](https://img3.doubanio.com/view/photo/l/public/p2555572656.jpg)

**全局作用域**

* 给所有模型定义公共作用域

* 继承`..\..\..\Scope` 类

* 实现`apply()`方法 在方法中书写全局作用域

* 使用`addSelect()`方法代替`select()`方法 以免覆盖

![](https://img1.doubanio.com/view/photo/l/public/p2555573429.jpg)

* 重写`Model`中`boot`方法

* 在`boot`方法中使用`addGlobalScope()`应用于全局作用域

```
// Book模型
protected static function boot()
{
    parent::boot();
    
    // 传入刚定义的全局作用域
    self::addGlobalScope(new BookScope);
}
```

* 也可以使用匿名全局作用域 省去新建类的操作


```
// Book模型
protected static function boot()
{
    parent::boot();
    
    // 传入全局作用域名称 和 匿名函数
    self::addGlobalScope('price_num', function (Builder $builder) {
        
        // 内容
    });
}
```

#### Seeder 和 模型工厂

生成一个Seeder

* `php artisan make:seeder TableNameSeeder`

* 在`run()`方法内定义要填充的数据

* 运行`php artisan db:seeder` 命令填充数据

* 还需要在记录`call()`方法里注册

**模型工厂**

* 批量插入数据

* `php artisan make:factory BookFactory`

```
public function run()
{
    factory(Book::class, 50)->create();
}
```

