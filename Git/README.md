![](https://ss0.bdstatic.com/70cFuHSh_Q1YnxGkpoWK1HF6hhy/it/u=3926926408,3148912474&fm=27&gp=0.jpg)

# Git指令

## 创建 

* 创建一个新的仓库  
`$ git init`

* 复制一个已创建的仓库  
`$ git clone https://github.com/Mrxxm/GitDocument.git`

## 本地修改

* 显示工作路径下全部已修改的文件  
`$ git status`

* 显示与上次提交版本文件的不同  
`$ git diff`

* 显示某个文件与上次提交版本文件的修改
`$ git diff Git/README.md`

* 把当前所有修改添加到下次提交中  
`$ git add .`

* 指定某个文件提交  
`$ git add Git/README.md`

* 提交本地所有修改  
`$ git commit -m "描述本次提交的文字"`

## 提交历史

* 查看提交记录  
`$ git log`

## 分支与标签

* 显示所有分支(远程和本地)  
`$ git branch -av`

* 显示本地分支  
`$ git branch`

* 切换当前分支  
`$ git checkout <branch>`

* 创建新分支(基于远程分支)  
`$ git checkout -b <new branch>` 

* 删除本地分支  
`$ git branch -d <branch>` 

* 给当前的提交打标签  
`$ git tag <tag-name>`

## 更新与发布

* 列出当前配置的远程端  
`$ git remote -v`

* 显示远程端信息  
`$ git remote show <remote>`

* 添加新的远程端  
`$ git remote add <shortname> <url>`

**下载远程端的所有改动到本地**  

* 自动合并到当前    
`$ git pull <remote> <branch>`

* 将本地版本发布到远程端  
`$ git push <remote> <branch>`

* 删除远程分支  
`$ git branch -dr <remote/branch>` 

* 发布标签  
`$ git push --tags` 

## 合并与重置

* 将分支合并到当前  
`$ git merge <branch>`

**将当前版本重置到分支** 
 
* 请勿重置已发布的提交  
`$ git rebase <branch>`

* 退出重置  
`$ git rebase --abort`

* 解决冲突后继续重置  
`$ git rebase --continue`

## 撤销

* 放弃工作目录下的所有修改  
`$ git reset --hard HEAD`  
或  
`$ git checkout .`

* 放弃某个文件的所有本地修改
`$ git checkout HEAD <file>`


## 实例

![](https://cdn.liaoxuefeng.com/cdn/files/attachments/001384907702917346729e9afbf4127b6dfbae9207af016000/0)

创建一个新仓库

```
$ git init
$ git add README.md
$ git commit -m "first commit”
这里跟的是ssh地址，在网页上创建仓库后，会随之生成。
$ git remote add origin https://github.com/Mrxxm/1.git       
$ git push -u origin master
```

```
1.git status             查看当前仓库状态
2.git add                把文件加到暂存区
3.git commit -m ""       把文件提交到暂存区，并增加说明
  git log                查看提交记录
4.git push origin master 把文件推到远程仓库
5.git pull origin master 把文件从远程仓库拉下来
```

取消add和取消commit

```

```





