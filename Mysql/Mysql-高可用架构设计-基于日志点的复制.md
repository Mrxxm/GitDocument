## 基于日志点的复制

#### 配置Mysql复制

基于日志点的复制配置步骤

* 在主DB服务器上建立复制账号 (`CREATE USER 'repl'@'IP段' identified by 'password';`) 
* 授权语句 (`GRANT REPLICATION SLAVE ON *.* TO 'repl'@'IP段';`)

配置主数据库服务器

* `bin_log = mysql-bin` 开启二进制日志并指定名称
* `server_id = 100` 动态参数，可以通过set命令进行配置 (还是要在配置文件中做相同的修改，否则下次重启服务配置就没有了)

配置从数据库服务器

* `bin_log = mysql-bin`
* `server_id = 100`
* `relay_log = mysql-relay-bin` 指定了中继日志的名字，只要启动主从复制，就默认启动，默认的名字是主机的名字
* `log_slave_update = on` 决定是否把SQL线程存放的中继日志存放到本机的二进制日志中 (如果要做链路复制，也就是把这个从服务器当做其他服务器的主服务器来进行复制时，这个参数就必须要配置了) [可选]
* `read_only = on` 安全配置参数，它可以阻止任何没有super权限的用户对开启了这个选项的数据库进行写操作 [可选] 

初始化从服务器日志

* `mysqldump --master-data=2 --single-transaction` 逻辑备份 (会对表进行加锁，影响并发性。为了保证事务的一致性，对Innodb表进行备份时，需要加上`--single-transaction`参数，如果是混合使用了Innodb和MyISAM则加上`--local-auto-tables`。另一个重要的参数就是`--master-data`用于记录在备份时主库当前二进制日志文件偏移量的信息，只有记录这两个值，我们才可以在从库上使用`change master to`命令来启动主从复制的链路)
* `xtrabackup --slave-info` 对Innodb存储引擎的数据库支持热备，对不是Innodb存储引擎的数据库同样会锁表，参数`--slave-info`记录主库的二进制日志和数据偏移量

启动复制链路 (这一步同样在从服务器上操作)

* `CHANGE MASTER TO MASTER_HOST = 'master_host_ip', MASTER_USER = 'repl', MSATER_PASSWORD = 'Password', MASTER_LOG_FILE = 'mysql_log_file_name', MASTER_LOG_POS = 4;` `MASTER_HOST`主数据库的地址，`MASTER_USER` `MSATER_PASSWORD` 第一步在主库上建立的供复制的账号密码

window1 (主库)

```

# 查看服务器信息
[root@kenrou ~]# curl ifconfig.me
118.25.93.*[root@kenrou ~]#

# 进入数据库 为从服务器创建repl用户 并设置密码
mysql> create user repl@'119.29.196.*' identified by 'Xxm&******';
Query OK, 0 rows affected (0.07 sec)

# 设置slave权限 全局权限 
mysql> grant replication slave on *.* to repl@'119.29.196.*';
Query OK, 0 rows affected (0.01 sec)

# 接下来配置参数
# vim /etc/my.cnf

# 添加以下两个参数
# log_bin
  log_bin=mysql-bin
  server-id=1
 
# 初始化从服务器的数据 (备份操作 如果主从服务器版本一致 可以备份所有数据库 将crn 改为 --all-databases)
[root@kenrou ~]# mysqldump --single-transaction --master-data --triggers --routines crn  -uroot -p >> all.sql
Enter password:

# 查看文件
[root@kenrou ~]# ll
总用量 29796
-rw-r--r--  1 root   root   10973760 11月 22 15:44 1.sql
-rw-r--r--  1 root   root       2036 11月 30 10:39 all.sql

# 将文件拷贝到从库服务器中
[root@kenrou ~]# scp all.sql root@119.29.196.*:/root
The authenticity of host '119.29.196.* (119.29.196.*)' can't be established.
ECDSA key fingerprint is SHA256:**********.
ECDSA key fingerprint is MD5:*******.
Are you sure you want to continue connecting (yes/no)? yes
Warning: Permanently added '119.29.196.*' (ECDSA) to the list of known hosts.
root@119.29.196.*'s password:
all.sql

# 配置完成 查看线程 (从服务器开启链路之后)
mysql> show processlist;
+-----+------+----------------------+------+-------------+------+---------------------------------------------------------------+------------------+
| Id  | User | Host                 | db   | Command     | Time | State                                                         | Info             |
+-----+------+----------------------+------+-------------+------+---------------------------------------------------------------+------------------+
| 138 | repl | 119.29.196.*:37***   | NULL | Binlog Dump |  105 | Master has sent all binlog to slave; waiting for more updates | NULL             |
| 139 | root | localhost            | NULL | Query       |    0 | starting                                                      | show processlist |
+-----+------+----------------------+------+-------------+------+---------------------------------------------------------------+------------------+
2 rows in set (0.00 sec)
```

window2 (从库)

```

# 查看服务器信息
[root@HUGE_DICK_MAN ~]# curl ifconfig.me
119.29.196.*[root@HUGE_DICK_MAN ~]#

# 接下来配置参数
# vim /etc/my.cnf

# 添加以下两个参数
# log_bin
  log_bin=mysql-bin
  server-id=2
  relay_log=mysqld-relay-bin
  
# 初始化从服务器的数据 (查看传输过来的all.sql文件)
[root@HUGE_DICK_MAN /]# cd root/
[root@HUGE_DICK_MAN ~]# ll
总用量 176216
-rw-r--r-- 1 root root      2036 11月 30 10:42 all.sql

# 从服务器的数据初始化 (备份单个数据库需要指定数据库)
[root@HUGE_DICK_MAN ~]# mysql -uroot -p < all.sql
Enter password:
ERROR 1046 (3D000) at line 28: No database selected

# 导入成功
[root@HUGE_DICK_MAN ~]# mysql -uroot -p crn < all.sql
Enter password:

# 接着进行数据链路的配置工作 (其中master_log_file和master_log_pos参数从all.sql文件中获取)
mysql> change master to master_host='118.25.93.*',
    -> master_user='repl',
    -> master_password='Xxm&******',
    -> master_log_file='mysql-bin.000006',
    -> MASTER_LOG_POS=2451;
Query OK, 0 rows affected, 2 warnings (0.04 sec)

# 查看复制链路状态 (Slave_IO_Running: No, Slave_SQL_Running: No 表示还未启动)
mysql> show slave status \G
*************************** 1. row ***************************
               Slave_IO_State:
                  Master_Host: 118.25.93.*
                  Master_User: repl
                  Master_Port: 3306
                Connect_Retry: 60
              Master_Log_File: mysql-bin.000006
          Read_Master_Log_Pos: 2451
               Relay_Log_File: mysqld-relay-bin.000001
                Relay_Log_Pos: 4
        Relay_Master_Log_File: mysql-bin.000006
             Slave_IO_Running: No
            Slave_SQL_Running: No
              Replicate_Do_DB:
          Replicate_Ignore_DB:
           Replicate_Do_Table:
       Replicate_Ignore_Table:
      Replicate_Wild_Do_Table:
  Replicate_Wild_Ignore_Table:
                   Last_Errno: 0
                   Last_Error:
                 Skip_Counter: 0
          Exec_Master_Log_Pos: 2451
              Relay_Log_Space: 154
              Until_Condition: None
               Until_Log_File:
                Until_Log_Pos: 0
           Master_SSL_Allowed: No
           Master_SSL_CA_File:
           Master_SSL_CA_Path:
              Master_SSL_Cert:
            Master_SSL_Cipher:
               Master_SSL_Key:
        Seconds_Behind_Master: NULL
Master_SSL_Verify_Server_Cert: No
                Last_IO_Errno: 0
                Last_IO_Error:
               Last_SQL_Errno: 0
               Last_SQL_Error:
  Replicate_Ignore_Server_Ids:
             Master_Server_Id: 0
                  Master_UUID:
             Master_Info_File: /var/lib/mysql/master.info
                    SQL_Delay: 0
          SQL_Remaining_Delay: NULL
      Slave_SQL_Running_State:
           Master_Retry_Count: 86400
                  Master_Bind:
      Last_IO_Error_Timestamp:
     Last_SQL_Error_Timestamp:
               Master_SSL_Crl:
           Master_SSL_Crlpath:
           Retrieved_Gtid_Set:
            Executed_Gtid_Set:
                Auto_Position: 0
         Replicate_Rewrite_DB:
                 Channel_Name:
           Master_TLS_Version:
1 row in set (0.00 sec)

# 开启复制链路
mysql> start slave;
Query OK, 0 rows affected (0.00 sec)

# 配置完成 查看线程
mysql> show processlist;
+----+-------------+-----------+------+---------+------+--------------------------------------------------------+------------------+
| Id | User        | Host      | db   | Command | Time | State                                                  | Info             |
+----+-------------+-----------+------+---------+------+--------------------------------------------------------+------------------+
|  6 | root        | localhost | NULL | Query   |    0 | starting                                               | show processlist |
|  8 | system user |           | NULL | Connect |   55 | Waiting for master to send event                       | NULL             |
|  9 | system user |           | NULL | Connect |   54 | Slave has read all relay log; waiting for more updates | NULL             |
+----+-------------+-----------+------+---------+------+--------------------------------------------------------+------------------+
3 rows in set (0.00 sec)
```


优点： 

* 是Mysql最早支持的复制技术，Bug相对较少
* 对SQL查询没有任何限制
* 故障处理比较容易

缺点：

* 故障转移时重新获取新主的日志点信息比较困难