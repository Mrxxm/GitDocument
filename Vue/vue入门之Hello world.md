# Hello world

### 目录

<!-- MarkdownTOC -->

- 说明
- 从零开始搭建VUE项目
    - 第一步：全局安装vue项目脚手架vue-cli
    - 第二步：利用上面安装的脚手架去自动化生成一个vue项目，名字叫做`vue-test`。
    - 第三步：进入项目目录，安装依赖
    - 第四部：启动开发模式
- 项目结构介绍
- Hello World
- 问题思考

<!-- /MarkdownTOC -->

### 说明

现代的前端开发环境基本上都是强依赖node环境的，也许你自己不知道，但其实，你已经是在写node的代码，但是通过打包转化为前端的代码，最终这些代码跑在浏览器的环境里，所以在此之前请确认自己知道node是个什么东西，npm是个什么东西，以及明白npm的常规用法。如：

- 知道如何初始化一个npm项目。
- 知道package.json文件的用处。
- 知道如何安装、更新、和删除一个npm包。
- 知道package.json内的配置项的用途。

本文章讲解基于vue-cli脚手架 和 vue-webpack-template模板快速搭建VUE项目的步骤，避免踩坑。同时后面的一下研究和学习内容也是基于这一套成熟的开发环境来展开的。所以，，，让我们开始吧。

### 从零开始搭建VUE项目

##### 第一步：全局安装vue项目脚手架vue-cli

打开终端，运行如下命令：

```sh
$ npm i -g vue-cli
```

##### 第二步：利用上面安装的脚手架去自动化生成一个vue项目，名字叫做`vue-test`。

```sh
$ vue init webpack vue-test

  This will install Vue 2.x version of the template.

  For Vue 1.x use: vue init webpack#1.0 vue-test

? Project name vue-test
? Project description A Vue.js project
? Author chenhaijiao <chenhaijiao@howzhi.com>
? Vue build standalone
? Install vue-router? Yes
? Use ESLint to lint your code? Yes
? Pick an ESLint preset Airbnb
? Setup unit tests with Karma + Mocha? Yes
? Setup e2e tests with Nightwatch? Yes

   vue-cli · Generated "vue-test".

   To get started:

     cd vue-test
     npm install
     npm run dev

   Documentation can be found at https://vuejs-templates.github.io/webpack

```

##### 第三步：进入项目目录，安装依赖

```js
$ cd vue-test && npm i 
```

##### 第四部：启动开发模式

```sh
$ npm run dev

> vue-test@1.0.0 dev /Users/Ben/Work/vue-test
> node build/dev-server.js

> Starting dev server...

 DONE  Compiled successfully in 3539ms

> Listening at http://localhost:8080
```

至此，你应该就已经看到一张自动打开的浏览器窗口，显示了VUE的log和一堆的友情链接。。。。

然后你就可以开始开发了。。。。

你可能问，，，，在哪里开发啊，代码写在哪里？请继续往下看。

### 项目结构介绍

```
.
├── build/                      # webpack 配置文件
│   └── ...
├── config/
│   ├── index.js                # 主要的项目配置
│   └── ...
├── src/
│   ├── main.js                 # 项目的入口js文件
│   ├── App.vue                 # 主要的App组件
│   ├── components/             # UI 组件文件夹
│   │   └── ...
│   └── assets/                 # 静态文件 (会被webpack打包)
│       └── ...
├── static/                     # 纯静态资源 (在打包生产环境的资源时，会被直接复制)
├── test/
│   └── unit/                   # 单元测试
│   │   ├── specs/              # 测试说明文件
│   │   ├── index.js            # 测试入口文件
│   │   └── karma.conf.js       # 测试runner的配置文件
│   └── e2e/                    # e2e 测试
│   │   ├── specs/              # 测试说明文件
│   │   ├── custom-assertions/  # 为e2e 定制的断言
│   │   ├── runner.js           # 测试 runner 脚本
│   │   └── nightwatch.conf.js  # 测试runner的配置文件
├── .babelrc                    # babel 配置
├── .postcssrc.js               # postcss 配置
├── .eslintrc.js                # eslint 配置
├── .editorconfig               # editor 配置
├── index.html                  # index.html 项目的唯一html入口文件
└── package.json                # 项目信息，包含打包脚本，依赖和开发以来等
```

不要惊讶，上面是我复制来的，只是对后面的文字描述做了中文翻译。

我们大部分的开发是在src文件夹下的，去不断的丰富main.js，在src/components文件夹下写大量的单文件组件，在src/router/index.js里面去写各种路由信息。

### Hello World

开发环境安装完成，我们来实现我们的Hello world!

找到 src/App.vue 文件，将`<template>`内的内容修改成如下，其它内容保持不变：

```html
<template>
  <div id="app">
    Hello World!
  </div>
</template>
```

修改完成后，回到浏览器查看页面。是不是发现页面已经出现了Hello World。

### 问题思考

但是。。。怎么开发VUE项目不是我们这一系列文档的重点，我们的重点是，

- 这个项目的开发环境是如何运行起来的？
- 为什么一个`npm run dev`你就可以边开发边打包边预览？
- 为什么index.html里面一个js的引入都没有？
- 这么好的开发体验是如何实现的？
- 我们从中能学到什么？

后面我们将深入的去研究这些问题。







