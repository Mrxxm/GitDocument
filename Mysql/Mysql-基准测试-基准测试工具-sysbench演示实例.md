## sysbench基准测试演示实例

window1 (测试cpu性能)

`/usr/share/sysbench`

```

# 测试cpu性能 指定cpu计算最大整数值10000 (测试单核的cpu性能)
[root@kenrou sysbench]# sysbench --test=cpu --cpu-max-prime=10000 run
WARNING: the --test option is deprecated. You can pass a script name or path on the command line without any options.
sysbench 1.0.15 (using bundled LuaJIT 2.1.0-beta2)

Running the test with following options:
Number of threads: 1
Initializing random number generator from current time


Prime numbers limit: 10000

Initializing worker threads...

Threads started!

CPU speed:
    events per second:   907.48

General statistics:
    total time:                          10.0003s
    total number of events:              9077

Latency (ms):
         min:                                    1.07
         avg:                                    1.10
         max:                                   19.36
         95th percentile:                        1.14
         sum:                                 9979.59

Threads fairness:
    events (avg/stddev):           9077.0000/0.00
    execution time (avg/stddev):   9.9796/0.00
```

window1 (测试磁盘IO)

```
# 查看内存大小
[root@kenrou sysbench]# free -m
              total        used        free      shared  buff/cache   available
Mem:           1838        1152          73          18         612         474
Swap:             0           0           0

# 查看磁盘空间
[root@kenrou sysbench]# df -lh
文件系统        容量  已用  可用 已用% 挂载点
/dev/vda1        50G   12G   36G   25% /
devtmpfs        909M     0  909M    0% /dev
tmpfs           920M   24K  920M    1% /dev/shm
tmpfs           920M  504K  919M    1% /run
tmpfs           920M     0  920M    0% /sys/fs/cgroup
tmpfs           184M     0  184M    0% /run/user/0

# 准备测试数据 要大于内存大小 
[root@kenrou tmp]# sysbench --test=fileio --file-total-size=3G prepare
WARNING: the --test option is deprecated. You can pass a script name or path on the command line without any options.
sysbench 1.0.15 (using bundled LuaJIT 2.1.0-beta2)

128 files, 24576Kb each, 3072Mb total
Creating files for the test...
Extra file open flags: (none)
Creating file test_file.0
Creating file test_file.1
Creating file test_file.2
Creating file test_file.3
Creating file test_file.4
Creating file test_file.5
Creating file test_file.6
Creating file test_file.7
Creating file test_file.8
Creating file test_file.9
···

# 查看文件大小
[root@kenrou tmp]# ls -lh
总用量 3.1G
drwxr-xr-x 5 root root 4.0K 11月 24 09:46 passenger.QgJbeKE
drwx------ 3 root root 4.0K 9月  15 23:47 systemd-private-bfc1ca18a2cc4200a013787f3a242bf0-ntpd.service-aA3zkn
-rw------- 1 root root  24M 11月 24 10:41 test_file.0
-rw------- 1 root root  24M 11月 24 10:41 test_file.1
-rw------- 1 root root  24M 11月 24 10:41 test_file.10
-rw------- 1 root root  24M 11月 24 10:41 test_file.100
-rw------- 1 root root  24M 11月 24 10:41 test_file.101
-rw------- 1 root root  24M 11月 24 10:41 test_file.102
-rw------- 1 root root  24M 11月 24 10:41 test_file.103
···

# 查看测试文件系统的配置参数
[root@kenrou tmp]# sysbench --test=fileio help
WARNING: the --test option is deprecated. You can pass a script name or path on the command line without any options.
sysbench 1.0.15 (using bundled LuaJIT 2.1.0-beta2)

fileio options:
  --file-num=N                  number of files to create [128]
  --file-block-size=N           block size to use in all IO operations [16384]
  --file-total-size=SIZE        total size of files to create [2G]
  
  # 顺序写入 顺序重写 顺序读取 随机读取 随机写入 混合随机读写
  --file-test-mode=STRING       test mode {seqwr, seqrewr, seqrd, rndrd, rndwr, rndrw}
  --file-io-mode=STRING         file operations mode {sync,async,mmap} [sync]
  --file-async-backlog=N        number of asynchronous operatons to queue per thread [128]
  --file-extra-flags=[LIST,...] list of additional flags to use to open files {sync,dsync,direct} []
  --file-fsync-freq=N           do fsync() after this number of requests (0 - don't use fsync()) [100]
  --file-fsync-all[=on|off]     do fsync() after each write operation [off]
  --file-fsync-end[=on|off]     do fsync() at the end of test [on]
  --file-fsync-mode=STRING      which method to use for synchronization {fsync, fdatasync} [fsync]
  --file-merged-requests=N      merge at most this number of IO requests if possible (0 - don't merge) [0]
  --file-rw-ratio=N             reads/writes ratio for combined test [1.5]
  
# 指定8个线程 混合随机读写 1秒间隔输出信息
[root@kenrou tmp]# sysbench --test=fileio --threads=8 --file-total-size=3G --file-test-mode=rndrw --report-interval=1 run
WARNING: the --test option is deprecated. You can pass a script name or path on the command line without any options.
sysbench 1.0.15 (using bundled LuaJIT 2.1.0-beta2)

Running the test with following options:
Number of threads: 8
Report intermediate results every 1 second(s)
Initializing random number generator from current time


Extra file open flags: (none)
128 files, 24MiB each
3GiB total file size
Block size 16KiB
Number of IO requests: 0
Read/Write ratio for combined random IO test: 1.50
Periodic FSYNC enabled, calling fsync() each 100 requests.
Calling fsync() at the end of test, Enabled.
Using synchronous I/O mode
Doing random r/w test
Initializing worker threads...

Threads started!

[ 1s ] reads: 8.60 MiB/s writes: 5.73 MiB/s fsyncs: 1148.58/s latency (ms,95%): 13.704
[ 2s ] reads: 10.70 MiB/s writes: 7.13 MiB/s fsyncs: 1409.36/s latency (ms,95%): 10.651
[ 3s ] reads: 10.73 MiB/s writes: 7.08 MiB/s fsyncs: 1456.85/s latency (ms,95%): 10.460
[ 4s ] reads: 7.50 MiB/s writes: 5.00 MiB/s fsyncs: 1059.79/s latency (ms,95%): 14.728
[ 5s ] reads: 12.25 MiB/s writes: 8.25 MiB/s fsyncs: 1707.40/s latency (ms,95%): 7.702
[ 6s ] reads: 13.06 MiB/s writes: 8.63 MiB/s fsyncs: 1682.28/s latency (ms,95%): 7.297
[ 7s ] reads: 11.58 MiB/s writes: 7.80 MiB/s fsyncs: 1645.90/s latency (ms,95%): 8.428
[ 8s ] reads: 9.98 MiB/s writes: 6.58 MiB/s fsyncs: 1393.78/s latency (ms,95%): 10.844
[ 9s ] reads: 11.30 MiB/s writes: 7.61 MiB/s fsyncs: 1545.17/s latency (ms,95%): 8.277
[ 10s ] reads: 12.14 MiB/s writes: 8.02 MiB/s fsyncs: 1599.97/s latency (ms,95%): 7.297

File operations:
    reads/s:                      670.57
    writes/s:                     446.56
    fsyncs/s:                     1523.39

Throughput:
    read, MiB/s:                  10.48
    written, MiB/s:               6.98

General statistics:
    total time:                          10.2924s
    total number of events:              26158

Latency (ms):
         min:                                    0.00
         avg:                                    3.06
         max:                                   75.40
         95th percentile:                        9.73
         sum:                                79958.27

Threads fairness:
    events (avg/stddev):           3269.7500/44.85
    execution time (avg/stddev):   9.9948/0.00
```

window (数据库性能测试)

```
# 创建数据库
mysql> create database imooc;
Query OK, 1 row affected (0.04 sec)

# 创建用户赋予最高权限
mysql> grant all privileges on *.* to sb@'localhost' identified by 'Xxm&123456';
Query OK, 0 rows affected, 1 warning (0.03 sec)

# 对Innodb数据库进行测试
[root@kenrou sysbench]# sysbench --test=./oltp_common.lua --mysql-table-engine=innodb --oltp-table-size=10000 --mysql-db=imooc --mysql-user=sb --mysql-password=Xxm&123456 --oltp-tables-count=10 --mysql-socket=/var/lib/mysql/mysql.sock prepare
[1] 27378
-bash: 123456: 未找到命令
[root@kenrou sysbench]# WARNING: the --test option is deprecated. You can pass a script name or path on the command line without any options.
sysbench 1.0.15 (using bundled LuaJIT 2.1.0-beta2)

FATAL: ./oltp_common.lua:28: Command is required. Supported commands: prepare, prewarm, run, cleanup, help
^C
[1]+  退出 1                sysbench --test=./oltp_common.lua --mysql-table-engine=innodb --oltp-table-size=10000 --mysql-db=imooc --mysql-user=sb --mysql-password=Xxm
```

TODO...