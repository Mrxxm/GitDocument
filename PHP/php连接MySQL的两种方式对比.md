## php连接MySQL的两种方式对比

php连接MySQL的两种方式对比，一种是原生的链接方式另外一种是PDO方式，附上示例，推荐给大家，有需要的小伙伴可以参考下。 
记录一下PHP连接MySQL的两种方式。  
先mock一下数据，可以执行一下sql。

```sql
/*创建数据库*/
CREATE DATABASE IF NOT EXISTS `test`;
/*选择数据库*/
USE `test`;
/*创建表*/
CREATE TABLE IF NOT EXISTS `user` (
  name varchar(50),
  age int
);
/*插入测试数据*/
INSERT INTO `user` (name, age) VALUES('harry', 20), ('tony', 23), ('harry', 24);
```

第一种是使用PHP原生的方式去连接数据库。代码如下：

```php
<?php
$host = 'localhost';
$database = 'test';
$username = 'root';
$password = 'root';
$selectName = 'harry';//要查找的用户名，一般是用户输入的信息
$connection = mysql_connect($host, $username, $password);//连接到数据库
mysql_query("set names 'utf8'");//编码转化
if (!$connection) {
  die("could not connect to the database.\n" . mysql_error());//诊断连接错误
}
$selectedDb = mysql_select_db($database);//选择数据库
if (!$selectedDb) {
  die("could not to the database\n" . mysql_error());
}
$selectName = mysql_real_escape_string($selectName);//防止SQL注入
$query = "select * from user where name = '$selectName'";//构建查询语句
$result = mysql_query($query);//执行查询
if (!$result) {
  die("could not to the database\n" . mysql_error());
}
while ($row = mysql_fetch_row($result)) {
  //取出结果并显示
  $name = $row[0];
  $age = $row[1];
  echo "Name: $name ";
  echo "Age: $age ";
  echo "\n";
}
```

其运行结构如下：  
Name: harry Age: 20  
Name: tony Age: 23  

第二种是使用PDO的方式去连接数据库，代码如下：  

```php
<?php
$host = 'localhost';
$database = 'test';
$username = 'root';
$password = 'root';
$selectName = 'harry';//要查找的用户名，一般是用户输入的信息
$pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);//创建一个pdo对象
$pdo->exec("set names 'utf8'");
$sql = "select * from user where name = ?";
$stmt = $pdo->prepare($sql);
$rs = $stmt->execute(array($selectName));
if ($rs) {
  // PDO::FETCH_ASSOC 关联数组形式
  // PDO::FETCH_NUM 数字索引数组形式
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $name = $row['name'];
    $age = $row['age'];
    echo "Name: $name ";
    echo "Age: $age ";
    echo "\n";
  }
}
$pdo = null;//关闭连接
```

其结果与第一种相同。
