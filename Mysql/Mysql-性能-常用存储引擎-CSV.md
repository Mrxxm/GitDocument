## 常用存储引擎-CSV

文件系统存储特点

* 数据以文本方式存储在文件中
* `.CSV`文件存储表内容
* `.CSM`文件存储表的元数据如表状态和数据量
* `.frm`文件存储表结构信息

特点

* 以CSV格式进行数据存储
* 所有列必须都是不能为NULL的
* 不支持索引 (不适合大表，不适合在线处理)
* 可以对数据文件直接编辑

window1 (设置表未csv存储引擎)

```sql
mysql> create table mycsv(id int, c1 varchar(10),c2 char(10)) engine = csv;

# 所有列必须都是不能为NULL的
ERROR 1178 (42000): The storage engine for the table doesn't support nullable columns

# 成功创建表
mysql> create table mycsv(id int not null, c1 varchar(10) not null,c2 char(10) not null) engine = csv;
Query OK, 0 rows affected (0.03 sec)

# 插入两行数据
mysql> insert into mycsv values(1, 'aaa', 'bbb'),(2, 'ccc', 'ddd');
Query OK, 2 rows affected (0.00 sec)
Records: 2  Duplicates: 0  Warnings: 0

mysql> select * from mycsv;
+----+-----+-----+
| id | c1  | c2  |
+----+-----+-----+
|  1 | aaa | bbb |
|  2 | ccc | ddd |
+----+-----+-----+
2 rows in set (0.00 sec)
```

window2 (文件系统中查看)

```
[root@kenrou test]# ll
total 164
-rw-r----- 1 mysql mysql    65 Nov 14 20:48 db.opt
-rw-r----- 1 mysql mysql    35 Nov 17 10:25 mycsv.CSM
-rw-r----- 1 mysql mysql    28 Nov 17 10:25 mycsv.CSV
-rw-r----- 1 mysql mysql  8608 Nov 17 10:23 mycsv.frm
[root@kenrou test]# cat mycsv.CSV
1,"aaa","bbb"
2,"ccc","ddd"

# 添加一行数据
[root@kenrou test]# vim mycsv.CSV
1,"aaa","bbb"
2,"ccc","ddd"
3,"eee","fff"
```

window1 (vim插入数据后查看)

```sql
mysql> flush tables;
Query OK, 0 rows affected (0.07 sec)

mysql> select * from mycsv;
+----+-----+-----+
| id | c1  | c2  |
+----+-----+-----+
|  1 | aaa | bbb |
|  2 | ccc | ddd |
|  3 | eee | fff |
+----+-----+-----+
3 rows in set (0.00 sec)

# 在csv表上添加索引
mysql> create index idx_id on mycsv(id);
ERROR 1069 (42000): Too many keys specified; max 0 keys allowed
```

适合场景

* 适合作为数据交换的中间表
* [电子表格] -> [CSV文件] -> [MYSQL数据目录]
* [数据] -> [CSV文件] -> [其他WEB程序]