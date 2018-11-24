## Mysql基准测试工具之sysbench

安装说明

* `https://github.com/akopytov/sysbench/archive/0.5.zip`
* `unzip sysbench-0.5.zip`
* `cd sysbench`
* `./autogen.sh`
* `./configure --with-mysql-includes=/usr/local/mysql/include/ --with-mysql-libs=/usr/local/mysql/lib/`
* `make && make install`


查看github,安装方式

```
https://github.com/akopytov/sysbench/#linux
```

* Debian/Ubuntu

```
curl -s https://packagecloud.io/install/repositories/akopytov/sysbench/script.deb.sh | sudo bash
sudo apt -y install sysbench
```

* RHEL/CentOS:

```
curl -s https://packagecloud.io/install/repositories/akopytov/sysbench/script.rpm.sh | sudo bash
sudo yum -y install sysbench
```

* Fedora:

```
curl -s https://packagecloud.io/install/repositories/akopytov/sysbench/script.rpm.sh | sudo bash	
sudo dnf -y install sysbench
```

* macOS

```
# Add --with-postgresql if you need PostgreSQL support
brew install sysbench
```

#### 常用参数

`--test` 用于指定所要执行的测试类型，支持以下参数

* `Fileio` 文件系统I/O性能测试
* `cpu` cpu性能测试
* `memory` 内存性能测试
* `Oltp` 测试要指定具体的lua脚本
* `Lua` 脚本位于`sysbench-0.5/sysbench/tests/db`

window1 (查看lua脚本)

```
[root@kenrou sysbench]# pwd
/usr/share/sysbench
[root@kenrou sysbench]# ls -lh *.lua
-rwxr-xr-x 1 root root 1.5K 7月   4 04:07 bulk_insert.lua
-rw-r--r-- 1 root root  15K 7月   4 04:07 oltp_common.lua
-rwxr-xr-x 1 root root 1.3K 7月   4 04:07 oltp_delete.lua
-rwxr-xr-x 1 root root 2.4K 7月   4 04:07 oltp_insert.lua
-rwxr-xr-x 1 root root 1.3K 7月   4 04:07 oltp_point_select.lua
-rwxr-xr-x 1 root root 1.7K 7月   4 04:07 oltp_read_only.lua
-rwxr-xr-x 1 root root 1.8K 7月   4 04:07 oltp_read_write.lua
-rwxr-xr-x 1 root root 1.1K 7月   4 04:07 oltp_update_index.lua
-rwxr-xr-x 1 root root 1.2K 7月   4 04:07 oltp_update_non_index.lua
-rwxr-xr-x 1 root root 1.5K 7月   4 04:07 oltp_write_only.lua
-rwxr-xr-x 1 root root 1.9K 7月   4 04:07 select_random_points.lua
-rwxr-xr-x 1 root root 2.1K 7月   4 04:07 select_random_ranges.lua
```

* `--myql-db` 用于指定执行基准测试的数据库名
* `--mysql-table-engine` 用于指定所使用的存储引擎
* `--oltp-tables-count` 执行测试的表的数量
* `--oltp-table-size` 指定每个表中数据行数
* `--num-threads` 指定测试的并发线程数量
* `--max-time` 指定最大的测试时间 (秒为单位)
* `--report-interval` 指定间隔多长时间输出一次统计信息
* `--mysql-user` 指定执行测试的MySQL用户
* `--mysql-password` 指定执行测试的MySQL用户的密码
* `prepare` 用于准备测试数据
* `run` 用于实际进行测试
* `cleanup` 用于清理测试数据
