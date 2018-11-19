## MySQL常用存储引擎-Memory

文件系统存储特点

* 也称HEAP存储引擎，所以数据保存在内存中

功能特点

* 支持HASH索引和BTree索引 (默认HASH索引，如果在做等值查询，HASH索引会非常的快，在做范围查询的话就不能使用HASH索引了)
* 所有字段都为固定长度 `varchar(10) = char(10)`
* 不支持BLOG和TEXT等大字段
* Memory存储引擎使用表级锁
* 表的最大大小由max_heap_table_size参数决定 (默认16MB，修改参数后，对于已存在的表需要重建才能生效)

window1 (索引特点)

```sql
# 不支持TEXT字段
mysql> create table mymemory(id int, c1 varchar(10), c2 char(10), c3 text) engine = 'memory';
ERROR 1163 (42000): The used table type doesn't support BLOB/TEXT columns

# 成功创建
mysql> create table mymemory(id int, c1 varchar(10), c2 char(10)) engine = 'memory';
Query OK, 0 rows affected (0.01 sec)

# 创建索引 不指定类型
mysql> create index idx_c1 on mymemory(c1);
Query OK, 0 rows affected (0.01 sec)
Records: 0  Duplicates: 0  Warnings: 0

# 创建索引 指定类型为btree
mysql> create index idx_c2 using btree on mymemory(c2);
Query OK, 0 rows affected (0.02 sec)
Records: 0  Duplicates: 0  Warnings: 0

# 查看索引
mysql> show index from mymemory\G
*************************** 1. row ***************************
        Table: mymemory
   Non_unique: 1
     Key_name: idx_c1
 Seq_in_index: 1
  Column_name: c1
    Collation: NULL
  Cardinality: 0
     Sub_part: NULL
       Packed: NULL
         Null: YES
   Index_type: HASH
      Comment:
Index_comment:
*************************** 2. row ***************************
        Table: mymemory
   Non_unique: 1
     Key_name: idx_c2
 Seq_in_index: 1
  Column_name: c2
    Collation: A
  Cardinality: NULL
     Sub_part: NULL
       Packed: NULL
         Null: YES
   Index_type: BTREE
      Comment:
Index_comment:
2 rows in set (0.02 sec)

# 查看索引信息 (所有列的长度都是固定的)
mysql> show table status like 'mymemory'\G
*************************** 1. row ***************************
           Name: mymemory
         Engine: MEMORY
        Version: 10
     Row_format: Fixed
           Rows: 0
 Avg_row_length: 26
    Data_length: 0
Max_data_length: 4406116
   Index_length: 0
      Data_free: 0
 Auto_increment: NULL
    Create_time: 2018-11-17 14:26:47
    Update_time: NULL
     Check_time: NULL
      Collation: latin1_swedish_ci
       Checksum: NULL
 Create_options:
        Comment:
1 row in set (0.02 sec)
```

window2 

```
[root@kenrou test]# ls -lh mymemory*;
-rw-r----- 1 mysql mysql 8.5K Nov 17 14:22 mymemory.frm
```

容易混淆的概念

* Memory存储引擎表 vs 临时表

Memory存储引擎表 (所有线程都可使用的)

临时表 (对当前的session是可见的)

* 系统使用临时表 (查询分析器、优化器产生 内部临时表)
* `create temporary table` 建立的临时表

系统使用临时表

* 超过限制使用Myisam临时表
* 未超过限制使用Memory表

使用场景

* 用于查找或者是映射表，例如邮编和地区的对应表
* 用于保存数据分析中产生的中间表
* 用于缓存周期性聚合数据的结果表

注意

* Memory数据易丢失，所以要求数据可再生