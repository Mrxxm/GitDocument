## ssh-keygen 基本用法

ssh 公钥认证是ssh认证的方式之一。通过公钥认证可实现ssh免密码登陆，git的ssh方式也是通过公钥进行认证的。

在用户目录的home目录下，有一个.ssh的目录，和当前用户ssh配置认证相关的文件，几乎都在这个目录下。

ssh-keygen 可用来生成ssh公钥认证所需的公钥和私钥文件。

```
使用 ssh-keygen 时，请先进入到 ~/.ssh 目录，不存在的话，请先创建。并且保证 ~/.ssh 以及所有父目录的权限不能大于 711
```
**进入~/.ssh 目录**

```
[root@kenrou ~]# cd ~
[root@kenrou ~]# ls -ah
.  ..  .bash_history  .bash_logout  .bash_profile  .bashrc  black.list  .cache  .config  .cshrc  .pip  .pydistutils.cfg  .ssh  ssh_deny.sh  .tcshrc  .viminfo
[root@kenrou ~]# cd .ssh
[root@kenrou .ssh]# ll
total 4
-rw------- 1 root root 403 Jun 13 10:16 authorized_keys
[root@kenrou .ssh]#
```

## 生成的文件名和文件位置

使用 ssh-kengen 会在~/.ssh/目录下生成两个文件，不指定文件名和密钥类型的时候，默认生成的两个文件是：

* id_rsa
* id_rsa.pub

第一个是私钥文件，第二个是公钥文件。

**1.使用 `ssh-keygen` 生成文件**

```
[root@kenrou .ssh]# ssh-keygen
Generating public/private rsa key pair.
Enter file in which to save the key (/root/.ssh/id_rsa): 
Enter passphrase (empty for no passphrase): 
Enter same passphrase again: 
Your identification has been saved in /root/.ssh/id_rsa.
Your public key has been saved in /root/.ssh/id_rsa.pub.
The key fingerprint is:
8d:3c:5c:7a:e3:2a:cb:ef:4d:2b:5a:7c:2e:ba:ae:0f root@kenrou
The key's randomart image is:
+--[ RSA 2048]----+
|                 |
|                 |
|          .      |
|       o =       |
|        S +      |
|       . + .     |
|    E   o +      |
|     o..o* .     |
|    .+OO+o+      |
+-----------------+
[root@kenrou .ssh]# ll
total 12
-rw------- 1 root root  403 Jun 13 10:16 authorized_keys
-rw------- 1 root root 1679 Jul  9 10:46 id_rsa
-rw-r--r-- 1 root root  393 Jul  9 10:46 id_rsa.pub
[root@kenrou .ssh]# 
```

详解

如果没有指定文件名，会询问你输入文件名:

```
[root@kenrou .ssh]# ssh-keygen
Generating public/private rsa key pair.
Enter file in which to save the key (/root/.ssh/id_rsa): 
···
```

你可以输入你想要的文件名。

之后，会询问你是否需要输入密码。输入密码之后，以后每次都要输入密码。请根据你的安全需要决定是否需要密码，如果不需要，直接回车:

```
···
Enter passphrase (empty for no passphrase): 
Enter same passphrase again: 
···
```

**2.使用 `ssh-keygen -f -C` 生成文件**

```
[root@kenrou .ssh]# ssh-keygen -f xxm -C "xxm key"
Generating public/private rsa key pair.
Enter passphrase (empty for no passphrase): 
Enter same passphrase again: 
Your identification has been saved in xxm.
Your public key has been saved in xxm.pub.
The key fingerprint is:
9e:df:58:d5:a1:b2:ac:3c:7f:94:2d:e4:0d:ea:b4:72 xxm key
The key's randomart image is:
+--[ RSA 2048]----+
|                 |
|                 |
|               . |
|             o...|
|        S  .+.* .|
|       . ..oo* o |
|        o oo+ .  |
|        .+.E .   |
|         oB.o    |
+-----------------+
[root@kenrou .ssh]# ll
total 12
-rw------- 1 root root  403 Jun 13 10:16 authorized_keys
-rw------- 1 root root 1679 Jul  9 10:49 xxm
-rw-r--r-- 1 root root  389 Jul  9 10:49 xxm.pub
[root@kenrou .ssh]# cat xxm.pub
ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDHUaIO7BYVrGC31S51pRFTG4/P5yAAMy+F47fHroz/LFLT0Kakdo0W3uuu50272QF10h2DO41PQiV7fOxxMrlkU3+SqVvj0yuGEmpe+k92k5gl1JPqpkQjYvK2of3OUR8sHGnau3mZknsCBua9pf8xdNUpd4pNixhTzvRisn6/AWyzR9FkuPPEMygTwPtsa0mzit1zltvFHx2MLcGiIiZwR97NBSnD0kRAKHm+uzSVxK2caF+7bi9oo4fouAeXZaz37YmGtFa27on4UaLV1RViFr0oMV7D2kmQOxCEB/wf8NjrrpT6mODLzvN6jUB0/VYsmDFDMXbJVMQRNdN/ZKQL xxm key
[root@kenrou .ssh]# 
```

详解

```
ssh-keygen -f xxm -C "xxm key"
```

* -f 后加文件名   
* -C 为公钥文件中的备注在 *.pub 文件中结尾处

```
[root@kenrou .ssh]# cat xxm.pub
ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDHUaIO7BYVrGC31S51pRFTG4/P5yAAMy+F47fHroz/LFLT0Kakdo0W3uuu50272QF10h2DO41PQiV7fOxxMrlkU3+SqVvj0yuGEmpe+k92k5gl1JPqpkQjYvK2of3OUR8sHGnau3mZknsCBua9pf8xdNUpd4pNixhTzvRisn6/AWyzR9FkuPPEMygTwPtsa0mzit1zltvFHx2MLcGiIiZwR97NBSnD0kRAKHm+uzSVxK2caF+7bi9oo4fouAeXZaz37YmGtFa27on4UaLV1RViFr0oMV7D2kmQOxCEB/wf8NjrrpT6mODLzvN6jUB0/VYsmDFDMXbJVMQRNdN/ZKQL xxm key
```

## 文件权限

为了让私钥文件和公钥文件能够在认证中起作用，请确保权限正确。

对于.ssh 以及父文件夹，当前用户用户一定要有执行权限，其他用户最多只能有执行权限。

对于公钥和私钥文件也是: 当前用户一定要有执行权限，其他用户最多只能有执行权限。

```
对于利用公钥登录，对其他用户配置执行权限是没有问题的。
但是对于git，公钥和私钥, 以及config等相关文件的权限，其他用户不可有任何权限。
```