![](https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1528117632187&di=d23b57bc791aeec224e569831369c084&imgtype=0&src=http%3A%2F%2Fpic.58pic.com%2F58pic%2F15%2F56%2F96%2F08T58PIC8yU_1024.png)

# Mac Terminal

---

* 显示：*(快捷键command + shift + .)*  
`$ defaults write com.apple.finder AppleShowAllFiles -bool true`

* 隐藏：*(快捷键command + shift + .)*  
`$ defaults write com.apple.finder AppleShowAllFiles -bool false` 

* 创建txt文件   
`$ pico`

* 更改权限  
`$ sudo chmod -R 777 /Users/tom`

* 查看进程  
`$ sudo lsof -nP -iTCP -sTCP:LISTEN`

## 基本命令
* **列出文件**    
`$ ls 参数 目录名 `    
   
* 看看驱动目录下有什么  
`$ ls /System/Library/Extensions`    
*参数 -w 显示中文，-l 详细信息， -a 包括隐藏文件*

* **转换目录**       
`$ cd`    

* 想到驱动目录下溜达一圈   
`$ cd /System/Library/Extensions`

* **建立新目录**  
`$ mkdir 目录名`
 
* 在驱动目录下建一个备份目录 backup       
`$ mkdir /System/Library/Extensions/backup`

* 在桌面上建一个备份目录 backup  
`$ mkdir /User/用户名/Desktop/backup`

* **拷贝文件**  
`$ cp 参数 源文件 目标文件`    

* 想把桌面的Natit.kext 拷贝到驱动目录中  
`$ cp -R /User/用户名/Desktop/Natit.kext /System/Library/Extensions`  
*参数R表示对目录进行递归操作，kext在图形界面下看起来是个文件，实际上是个文件夹。*

* 把驱动目录下的所有文件备份到桌面backup  
`$ cp -R /System/Library/Extensions/* /User/用户名/Desktop/backup`

* **删除文件**  
`$ rm 参数 文件`

* 想删除驱动的缓存  
`$ rm -rf /System/Library/Extensions.kextcache`  
`$ rm -rf /System/Library/Extensions.mkext`    
*参数－rf 表示递归和强制，千万要小心使用，如果执行了 rm -rf / 你的系统就全没了*

* **移动文件**  
`$ mv 文件`   

* 想把AppleHDA.Kext 移到桌面  
`$ mv /System/Library/Extensions/AppleHDA.kext /User/用户名/Desktop`  

* 想把AppleHDA.Kext 移到备份目录中    
`$ mv /System/Library/Extensions/AppleHDA.kext /System/Library/Extensions/backup`  

* **文本编辑**  
`$ nano 文件名`
   
* 编辑natit Info.plist       
`$ nano /System/Library/Extensions/Natit.kext/Info.plist`

**目录操作**

命令名         | 功能描述           |     使用举例      |
------------- | -------------    | ----------       |
mkdir         | 创建一个目录       |  mkdir dirname   |
rmdir         | 删除一个目录       |  rmdir dirname   |
mvdir         | 移动或重命名一个目录|  mvdir dir1 dir2 |
cd            | 改变当前目录       |  cd dirname      |
pwd           | 显示当前目录的路径名|  pwd             |
ls            | 显示当前目录的内容  |  ls -la          |

**文件操作**

命令名         | 功能描述           |     使用举例      |
------------- | -------------    | ----------       |
cat           | 显示或连接文件      |  cat filename   |
od            |  显示非文本文件的内容|  od -c filename   |
cp            | 复制文件或目录     |  cp file1 file2 |
rm            | 删除文件或目录       |  rm filename      |
mv            | 改变文件名或所在目录|   mv file1 file2    |
find          | 使用匹配表达式查找文件  |  find . -name "*.c" -print  |
file          | 显示文件类型  |  file filename  |

**选择操作**

命令名         | 功能描述           |     使用举例      |
------------- | -------------    | ----------       |
head         | 显示文件的最初几行       |   head -20 filename   |
tail         | 显示文件的最后几行       |  tail -15 filename   |
cut         | 显示文件每行中的某些域    |  cut -f1,7 -d: /etc/passwd |
colrm         | 从标准输入中删除若干列       |  colrm 8 20 file2      |
diff           | 比较并显示两个文件的差异|  diff file1 file2        |
sort            | 排序或归并文件  |  sort -d -f -u file1          |
uniq            | 去掉文件中的重复行  |  uniq file1 file2          |
comm            | 显示两有序文件的公共和非公共行  |  comm file1 file2   |
wc            | 统计文件的字符数、词数和行数  |  wc filename          |
nl            | 给文件加上行号  |  nl file1 >file2          |


**进程操作**

命令名         | 功能描述           |     使用举例      |
------------- | -------------    | ----------       |
ps         | 显示进程当前状态       |   ps u   |
kill           | 终止进程       |  kill -9 30142   |

**时间操作**

命令名         | 功能描述           |     使用举例      |
------------- | -------------    | ----------       |
date         | 显示系统的当前日期和时间    |   date   |
cal          | 显示日历       |  cal 8 1996   |
time          | 统计程序的执行时间       |   time a.out   |

**网络与通信操作**

命令名         | 功能描述                 |     使用举例   |
------------- | -------------          | ----------    |
telnet         | 远程登录    |   telnet hpc.sp.net.edu.cn       |
rlogin          | 远程登录    |  rlogin hostname -l username   |
rsh          | 在远程主机执行指定命令        |   rsh f01n03 date  |
ftp         | 在本地主机与远程主机之间传输文件     |   ftp ftp.sp.net.edu.cn        |
rcp          | 在本地主机与远程主机 之间复制文件  |  rcp file1 host1:file2   |
ping          | 给一个网络主机发送 回应请求      |    ping hpc.sp.net.edu.cn  |
mail         | 阅读和发送电子邮件    |    mail        |
write          | 给另一用户发送报文                 |   write username pts/1   |
mesg          | 允许或拒绝接收报文        |    mesg n  |

**Korn Shell 命令**

命令名         | 功能描述           |     使用举例      |
------------- | -------------    | ----------       |
history         | 列出最近执行过的 几条命令及编号    |   history   |
r           | 重复执行最近执行过的 某条命令       |  r -2   |
alias          | 给某个命令定义别名       |   alias del=rm -i   |
unalias          |  取消对某个别名的定义       |   unalias del   |

**其它命令**

命令名         | 功能描述           |     使用举例      |
------------- | -------------    | ----------       |
uname         | 显示操作系统的有关信息       |   uname -a   |
clear           | 清除屏幕或窗口内容       |  clear   |
env            | 显示当前所有设置过的环境变量       |  env   |
who           | 列出当前登录的所有用户       |   who   |
whoami           | 显示当前正进行操作的用户名       |  whoami   |
tty            | 显示终端或伪终端的名称       |  tty   |
stty           | 显示或重置控制键定义       |  stty -a   |
du           | 查询磁盘使用情况        |  du -k subdir   |
df /tmp           | 显示文件系统的总空间和可用空间       |     |
w           | 显示当前系统活动的总信息       |    |


## Mac 开启crontab定时任务调试

cron来源于希腊单词chronos（意为“时间”），是linux系统下一个自动执行指定任务的程序。例如，你想在每晚睡觉期间创建某些文件或文件夹的备份，就可以用cron来自动执行。

**Mac使用开启crontab**
  
* 查看 crontab 是否启动    
`$ sudo launchctl list | grep cron`  

* 检查需要的文件    
`$ LaunchAgents  ll /etc/crontab`    
` ls: /etc/crontab: No such file or directory  #表示没有这个文件，需要创建一个`

* 创建文件  
`$ sudo touch /etc/crontab`  

**crontab服务的重启关闭，开启**

* mac 

```
$ sudo /usr/sbin/cron start
$ sudo /usr/sbin/cron restart
$ sudo /usr/sbin/cron stop
```

* ubuntu

```
$ sudo /etc/init.d/cron start
$ sudo /etc/init.d/cron stop
$ sudo /etc/init.d/cron restart
```

* linux

```
# /sbin/service crond start
# /sbin/service crond stop
# /sbin/service crond restart
# /sbin/service crond reload
```

* 查看当前用户的cron配置  
`$ sudo crontab -l `
     
* 编辑当前用户的cron配置  
`$ sudo crontab -e `
     
* 删除当前用户的cron配置  
`$ sudo crontab -r`

每分钟输出当前时间到time.txt上  
`*/1 * * * * /bin/date >> /User/Username(你的用户名)/time.txt`

**crontab的文件格式**  

* 第1列分钟0～59

* 第2列小时0～23（0表示子夜）

* 第3列日1～31

* 第4列月1～12

* 第5列星期0～7（0和7表示星期天）

* 第6列要运行的命令

## 终端 输入中文问题

* mac
 
```
$ locale
LANG="en_US.UTF-8"
LC_COLLATE="en_US.UTF-8"
LC_CTYPE="en_US.UTF-8"
LC_MESSAGES="en_US.UTF-8"
LC_MONETARY="en_US.UTF-8"
LC_NUMERIC="en_US.UTF-8"
LC_TIME="en_US.UTF-8"
LC_ALL="en_US.UTF-8"
```

**输入后重启**

```
$ export LC_ALL=en_US.UTF-8
$ export LANG=en_US.UTF-8
```

* linux

```
# locale
LANG=en_US.UTF-8
LANGUAGE=
LC_CTYPE="en_US.UTF-8"
LC_NUMERIC="en_US.UTF-8"
LC_TIME="en_US.UTF-8"
LC_COLLATE="en_US.UTF-8"
LC_MONETARY="en_US.UTF-8"
LC_MESSAGES="en_US.UTF-8"
LC_PAPER="en_US.UTF-8"
LC_NAME="en_US.UTF-8"
LC_ADDRESS="en_US.UTF-8"
LC_TELEPHONE="en_US.UTF-8"
LC_MEASUREMENT="en_US.UTF-8"
LC_IDENTIFICATION="en_US.UTF-8"
LC_ALL=en_US.UTF-8
```

**输入后重启**

```
# export LC_ALL=en_US.UTF-8
# export LANG=en_US.UTF-8
```
