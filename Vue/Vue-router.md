## Vue-router实现原理

`Vue-router`实现原理：

```
<body>
  <a href="#/login">登录管理</a>
  <a href="#/register">注册管理</a>
  <div id="app"></div>
  <script type="text/javascript">
    var oDiv = document.getElementById('app');
    
    window.onhashchange = function() {
      console.log(location.hash);
      switch (location.hash) {
        case '#/login': 
          oDiv.innerHTML = '<h2>登录页面</h2>'
          break;
        case '#/register':
          oDiv.innerHTML = '<h2>注册页面</h2>'
          break;
        default:
      }
    }
  </script>
</body>
```

## Vue-router基本使用

下载：

* `npm install vue-router`

引入`vue-router`模块,抛出两个全局的组件 `router-link`和`router-view`：

* `router-link`：渲染`<a>`标签，其中的`to`属性渲染成`href`
* `router-view`：路由匹配组件的出口

`vue-router`基本使用：

```
var Login = {
    template:'<h2>登录页面</h2>'
};

var Register = {
    template:'<h2>注册页面</h2>'
};

var router = new VueRouter({
    routes:[
        { path:"/login", component:Login},
        { path:"/register", component:Register}
    ]
});

var App = {
    template:'<div><router-link to="/login">登录管理</router-link> <router-link to="/register">注册管理</router-link><router-view></router-view></div>'
}

new Vue({
    el:"#app",
    data() {
        return {

        }
    },
    template:'<App />',
    components:{
        App
    },
    router: router,
});
```

#### 路由命名

路由命名：

* 添加`name`属性
* `:to`属性用绑定值

```
var router = new VueRouter({
    routes:[
        { path:"/login", name:'login', component:Login},
        { path:"/register", name:'register', component:Register}
    ]
});

var App = {
    template:'<div><router-link :to="{name:\'login\'}">登录管理</router-link> <router-link :to="{name:\'register\'}">注册管理</router-link><router-view></router-view></div>'
}
```

#### 路由参数

路由参数：

* `/user/:id`：`params:{id:1}`
* `/user`： `query:{userId:2}`

```
var router = new VueRouter({
    routes:[
        { path:"/user/:id", name:'userP', component:UserParams},
        { path:"/user", name:'userQ', component:UserQuery}
    ]
});

var App = {
    template:'<div><router-link :to="{name:\'userP\', params:{id:1}}">用户一</router-link> <router-link :to="{name:\'userQ\', query:{userId:2}}">用户二</router-link><router-view></router-view></div>'
}
```

#### 路由参数获取

路由参数获取：

* `this.$router`
* `this.$route`

```
var UserParams = {
    template:'<h2>我是用户一</h2>',
    created() {
        console.log(this.$router);
        console.log(this.$route);
        this.$route.params.id;
    }
};
```

#### 嵌套路由

路由嵌套：

* `children`

```
var App = {
    template:'<div><router-link :to="{name:\'home\'}">首页</router-link> <router-view></router-view></div>'
}

var Home = {
    template:'<div>首页内容<br /><router-link to="/home/song">歌曲</router-link> <router-view></router-view></div>'
};

var Song = {
    template:'<div>歌曲内容</div>'
}

var router = new VueRouter({
    routes:[
        { path:"/home", name:'home', component:Home, children:[ { path:"song", component:Song } ]}
    ]
});
```

#### 动态路由匹配


动态路由匹配：

* 使用同一个模板，减少渲染
* 减少渲染，通过`watch`方法获取切换状态
* `mode:'history'`去除了路由上的`#`

```
var App = {
    template:'<div><router-link to="/timeline">首页</router-link><router-view></router-view></div>'
}

var Timeline = {
    template:'<div id="timeline"><router-link :to="{name:\'comDesc\', params:{id:\'frontend\'}}">前端</router-link><router-link :to="{name:\'comDesc\', params:{id:\'backend\'}}">后端</router-link><router-view></router-view></div>'
};

// 公共内容(路由的切换，不会重新渲染页面，即不会调用该模板的生命周期钩子)
var ComDesc = {
    template:'<div>{{msg}}</div>',
    data(){
        return {
            msg:''
        }
    },
    // 第一次点击时，加载
    created(){
        this.msg = this.$route.params.id;
    },
    // 除第一次，切换点击时，加载
    watch:{
        '$route'(to, from) {
            console.log(from);
            console.log(to);
            this.msg = to.params.id;
        }
    }
}

var router = new VueRouter({
    routes:[
        { path:"/timeline", component:Timeline, children:[ { path:"/timeline/:id", name:'comDesc', component:ComDesc } ]}
    ]
});
```

#### `keep-alive`在路由中的使用

* 将`router-view`用`keep-alive`包裹，页面只渲染一次

```
var App = {
    template:'<div><router-link to="/timeline">首页</router-link><router-link to="/pins">沸点</router-link><keep-alive><router-view></router-view></keep-alive></div>'
}

var Timeline = {
    template:'<div>首页内容</div>',
    created() {
        console.log('首页')
    },
    mounted(){
        console.log('首页加载了')
    },
    destroyed(){
        console.log('首页销毁了')
    }
};

var Pins = {
    template:'<div>沸点内容</div>',
    created() {
        console.log('沸点')
    },
    mounted(){
        console.log('沸点加载了')
    },
    destroyed(){
        console.log('沸点销毁了')
    }
}

var router = new VueRouter({
    routes:[
        { path:"/timeline", component:Timeline },
        { path:"/pins", component:Pins }
    ]
});
```

#### 路由的元信息

* vue-router基本使用-meta的使用-权限控制

在访问`/blog`路由时，需要登录用户才能访问。通过定义路由的`meta`属性，使用`meta`属性值来判断路由是否需要路由权限验证。

如何使用呢？

使用全局守卫：`router.beforeEach()`，路由切换时会调用，所谓的路由守卫就是钩子函数。还有一点要注意的是，一定要调用`next()`函数，不然路由跳转将会卡住。


