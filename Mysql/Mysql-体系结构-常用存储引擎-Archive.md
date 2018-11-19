## 常用存储引擎-Archive

文件存储引擎特点

* 以zlib对表数据进行压缩，磁盘I/O更少
* 数据存储在ARZ为后缀的文件中

Archive存储引擎的特点

* 只支持insert和select操作
* 只允许在自增ID列上加索引

window1 (添加表)

```sql

mysql> create table myarchive(id int auto_increment not null, c1 varchar(10), c2 char(10), key(id)) engine = archive;
Query OK, 0 rows affected (0.11 sec)
```

window2 (文件系统中查看)

```
[root@kenrou test]# ls -lh myarchive*;
-rw-r----- 1 mysql mysql 8.5K Nov 17 11:18 myarchive.ARZ
-rw-r----- 1 mysql mysql 8.5K Nov 17 11:18 myarchive.frm
```

window1 (验证是否支持删除、更新和在非自增字段上建立索引操作)

```sql

mysql> insert into myarchive(c1, c2) values('aa', 'bb'),('cc', 'dd');
Query OK, 2 rows affected (0.00 sec)
Records: 2  Duplicates: 0  Warnings: 0

mysql> select * from myarchive;
+----+------+------+
| id | c1   | c2   |
+----+------+------+
|  1 | aa   | bb   |
|  2 | cc   | dd   |
+----+------+------+
2 rows in set (0.01 sec)

# 不支持删除操作
mysql> delete from myarchive where id = 1;
ERROR 1031 (HY000): Table storage engine for 'myarchive' doesn't have this option

# 不支持更新操作
mysql> update myarchive set c1 = "aaa" where id = 1;
ERROR 1031 (HY000): Table storage engine for 'myarchive' doesn't have this option

# 不支持在非增字段上添加索引
mysql> create index idx_c1 on myarchive(c1);
ERROR 1069 (42000): Too many keys specified; max 1 keys allowed
```

适用场景

* 日志和数据采集类应用