## MySQL服务器参数介绍

Mysql获取配置信息路径

* 命令行参数 (`mysql_safe --datadir=/data/sql_data`)

* 配置文件 (centOS系统中 `/etc/my.cnf`)

查询读取配置

```
# 查询命令
[root@kenrou ~]# mysqld --help --verbose | grep -A 1 'Default options'
Default options are read from the following files in the given order:

# 路径
/etc/my.cnf /etc/mysql/my.cnf /usr/etc/my.cnf ~/.my.cnf
```

Mysql配置参数的作用域

* 全局参数  
`set global 参数名 = 参数值;`  
`set @@global.参数名 := 参数值;`

* 会话参数  
`set [session] 参数名 = 参数值;`  
`set @@session.参数名 := 参数值;`

window1 (设置完全局参数后，需要重新退出后再进入才生效)

```sql
mysql> show variables where variable_name='wait_timeout' or variable_name = 'interactive_timeout';
+---------------------+-------+
| Variable_name       | Value |
+---------------------+-------+
| interactive_timeout | 28800 |
| wait_timeout        | 28800 |
+---------------------+-------+
2 rows in set (0.06 sec)

# 在window2连接之后修改参数
mysql> set global wait_timeout = 3600;set global interactive_timeout = 3600;
Query OK, 0 rows affected (0.00 sec)

Query OK, 0 rows affected (0.00 sec)

```

window2

```sql
mysql> show variables where variable_name='wait_timeout' or variable_name = 'interactive_timeout';
+---------------------+-------+
| Variable_name       | Value |
+---------------------+-------+
| interactive_timeout | 28800 |
| wait_timeout        | 28800 |
+---------------------+-------+
2 rows in set (0.00 sec)

mysql> exit;
Bye
[root@kenrou ~]# mysql -uroot -p123456
mysql: [Warning] Using a password on the command line interface can be insecure.
Welcome to the MySQL monitor.  Commands end with ; or \g.
Your MySQL connection id is 182
Server version: 5.7.23 MySQL Community Server (GPL)

Copyright (c) 2000, 2018, Oracle and/or its affiliates. All rights reserved.

Oracle is a registered trademark of Oracle Corporation and/or its
affiliates. Other names may be trademarks of their respective
owners.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

mysql> show variables where variable_name='wait_timeout' or variable_name = 'interactive_timeout';
+---------------------+-------+
| Variable_name       | Value |
+---------------------+-------+
| interactive_timeout | 3600  |
| wait_timeout        | 3600  |
+---------------------+-------+
2 rows in set (0.01 sec)

```