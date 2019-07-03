## Vue-模板语法-插值

`package-lock.json`是管理库的版本。

安装:

* `$ npm install vue`

引包:

* `<script type="text/javascript" src="./node_modules/vue/dist/vue.js"></script>`

`Vue.js` 的核心是一个允许采用简洁的模板语法来声明式地将数据渲染进 `DOM` 的系统：

* 元素反转 `this.message.split('').reverse().join('')`

```
<div id="app">
  {{ message }}
</div>
```

```
var app = new Vue({
  el: '#app',
  data: {
    message: 'Hello Vue!'
  }
})
```

## Vue-模板语法-指令

* 在`vue`中，以`v-xxx`开头的就叫指令
* 指令封装了一些DOM行为，结合属性作为一个暗号


常用的指令：

* `v-text`:`innerText`
* `v-html`:`innerHTML`
* `v-if`:判断(对页面的内存消耗更大比起`v-show`)
* `v-else-if`
* `v-else`
* `v-show`:隐藏元素 如果确定要隐藏，会给元素的`style`加上`display：none`。是基于`css`样式切换
* `v-bind`:绑定标签上的属性(内置和自定义属性都行)
* `v-on`:绑定原生事件名
* `v-for`:遍历
* `v-model`:双向绑定，有`value`属性就能绑定
* **如果做form表单，绝对不能用display**

`v-bind`指令,绑定`class`属性：

```
<style type="text/css">
    .box{
        width:300px;
        height:300px;
        background-color:red;
    }
    .active{
        background-color:green;
    }
</style>
```

```
template:'
    <div class="app">
        <div class="box" v-bind:class="{active:true}"></div>
    </div>
'
```

`v-bind`指令，缩写：

```
<!-- 完整语法 -->
<a v-bind:href="url">...</a>

<!-- 缩写 -->
<a :href="url">...</a>
```

`v-on`指令，绑定事件：

```
template:'
    <div class="app">
        <div class="box" v-on:click='clickHandler'></div>
    </div>
'
```

```
method:{
    clickHandler(e) {
        // TODO...
    }
}
```

`v-on`指令，缩写：

```
<!-- 完整语法 -->
<a v-on:click="doSomething">...</a>

<!-- 缩写 -->
<a @click="doSomething">...</a>
```


`v-for`指令，遍历数组和对象：

```
data() {
  return {
    menuLists: [
        {id:1, name:'大腰子'}
        {id:2, name:'大腰'}
        {id:3, name:'腰子'}
    ],
    person: {
        name: '大萌',
        age: 20
    }
  }   
}
```

```
<ul>
    <li v-for="(item, index) in menuLists">
        <h5 v-text="item.id"></h5>
        <h3>{{ item.name }}</h3>
    </li>
</ul>

<ul>
    <li v-for="(value, key) in person">
        {{key}} : {{value}}
    </li>
</ul>
```

## Vue-双向数据绑定

`v-model`: 双向数据绑定的体现，**只能应用在有value属性的**

语法糖：它是`v-bind:value` 和 `v-on:input`的体现

* `e.target`：当前事件的目标对象
* `this`：当前实例化对象 

```
<input type="text" v-bind:value="msg" v-on:input="valueChange">
```

```
data(){
    return {
        msg:''
    }
},
methods:{
    valueChange(e) {
        this.msg = e.target.value;
    }
}
```

`v-model`实现方式：

```
<input type="text" v-mode="msg">
```

```
data(){
    return {
        msg:''
    }
},
```

## Vue-组件

`pay-admin`项目中的`main.js`:

* 如果`template`中定义了内容，那么优先加载`template`，如果没有定义内容那么加载的是`#app`的模板

* `template`：入口组件(组件：包含html，css，js)

* `<App/>`：创建的入口组件`App`标签



```
new Vue({
  el: '#app',
  router,
  store, //状态管理
  template: '<App/>',
  components: {
    App
  }
})
```

## Vue-组件的创建

#### 局部入口组件的创建

* 声明组件
* 挂载组件
* 使用组件


局部入口组件的声明：

* `$parent`：指向的是实例化对象(代表上一级对象)
* `$root`：指向的是实例化对象(代表根的对象)

```
// App组件对象
var AppName = {
    data() {
        return {
        
        }
    },
    template:'
        <div>
            我是入口组件
        </div>
    '
}
```

```
// 实例化对象
var vm = new Vue({
    el:"#app",
    data() {
        return {
        
        }
    },
    // 挂载子组件
    components:{
        AppName
    },
    // 父组件直接使用
    template:'
        <app-name></app-name>
    '
})
```

#### 全局组件的创建

全局组件的声明与挂载：

* 直接在`template`中使用闭合标签即可

```
Vue.component('VBtn', {
    template:'
        <button>按钮</button>
    '
})
```

## Vue-组件通信

数据从父组件流向子组件，引出组件通信。

#### 父组件向子组件通信

* `props`：子组件接收父组件的传值

父组件：

* 1.先给父组件中绑定自定义属性
* 2.在子组件中使用`props`接收父组件传递的数据
* 3.可以在子组件中任意使用

```
Vue.component('Parent', {
    data(){
        return {
            msg: '父组件数据',
        }
    },
    template:'
        <div>
            <p>我是父组件</p>
            <Child :childData='msg'>
        </div>
    '
})
```

子组件：

```
Vue.component('Child', {
    props:['childData']
    template:'
        <div>
            <p>我是子组件</p>
        </div>
    '
})
```

#### 子组件向父组件通信

* 自定义事件来触发

父组件：

* 1.在父组件中绑定自定义的事件
* 2.在子组件中触发原生的事件，在函数中使用`$emit`触发自定义的`childHandler`

```
Vue.component('Parent', {
    data(){
        return {
            msg: '父组件数据',
        }
    },
    template:'
        <div>
            <p>我是父组件</p>
            <Child :childData='msg' @childHandler1='childHandler' />
        </div>
    ',
    methods:{
        childHandler(val) {
            console.log(val);
        }
    }
})
```

子组件：

* `$emit`中的`$`代表挂载在当前组件对象上
* `$emit(自定义的事件名，消息)`
* 自定义的事件一定通过`this.$emit()`去触发

```
Vue.component('Child', {
    props:['childData']
    template:'
        <div>
            <p>我是子组件</p>
            <input type="text" v-model="childData" @input='changeValue(childData)'>
        </div>
    ',
    methods:{
        changeValue(val) {
            // $emit(自定义的事件名，消息)
            this.$emit('childHandler1', val);
        }
    }
})
```

## Vue-内置组件-插槽

* 插槽-内置组件 `slot` 作为承载分发内容的出口

全局组件的声明与挂载：

* `slot`类似实现重写的功能

```
Vue.component('VBtn', {
    template:'
        <button>
            <slot>
                按钮
            </slot>
        </button>
    '
})
```

组件使用：

```
var vm = new Vue({
    el:"#app",
    data() {
        return {
        
        }
    },
    template:'
        <div>
            <VBtn >登录</VBtn>
            <VBtn>注册</VBtn>
        </div>
    '
})
```

#### 按钮样式封装

* 实现`Element`组件按钮样式封装

全局组件的声明与挂载：

```
<style type="text/css">
    .dafalut{
        display: inline-block;
        line-height: 1;
        white-space: nowrap;
        cursor: pointer;
        background: #fff;
        border: 1px solid #dcdfe6;
        color: #606266;
        -webkit-appearance: none;
        text-align: center;
        box-sizing: border-box;
        outline: none;
        margin: 0;
        transition: .1s;
        font-weight: 500;
        -moz-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
        padding: 12px 20px;
        font-size: 14px;
        border-radius: 4px;
    }
    .primary{
        color: #fff;
        background-color: #409eff;
        border-color: #409eff;
    }
    .success{
        color: #fff;
        background-color: #67c23a;
        border-color: #67c23a;
    } 
</style>

<script type="text/javascript">
    Vue.component('VBtn', {
        props:[type],
        template:'
            <button class="default" :class="type">
                <slot>
                    按钮
                </slot>
            </button>
        '
    })
```

组件使用：

* 使用了父组件向子组件通信
* 写`type`表示的是里面的字符串
* 写`:type`表示的是绑定在`data`中的变量

```
var vm = new Vue({
    el:"#app",
    data() {
        return {
        
        }
    },
    template:'
      <div>
        <VBtn type="primary">主要按钮</VBtn>
        <VBtn type="success">成功按钮</VBtn>
      </div>
    '
})

</script>
```

## Vue-具名插槽

```
<slot name="two"></slot>
```

## Vue-过滤器

过滤器：为页面数据进行修饰功能

* 局部过滤器
* 全局过滤器

局部过滤器：

* 声明过滤器
* 使用过滤器`{{ 数据|过滤器名字 }}`

```
data(){
    return {
        price:0
    }
},
template:'
    <div>
        <input type="number" v-model="price" />
        <h3>{{ price|myFilter }}</h3>
    </div>
',
filters:{
    myFilter:function(value) {
        return "¥" + value;
    }
}
```

全局过滤器：

* 声明
* 调用方式于局部过滤器一致
* 过滤器传值方式(调用时：`{{ 数据|myReverse(arg) }}` 声明时：`function(数据, arg) {}`)

```
Vue.filter('myReverse', function(value) {
    return value.split('').reverse('').join('');
})
```

## Vue-watch监听

简单监听和深度监听。

* 字符串就直接监听其内存地址
* 当监听对象为一个数组时(属于复杂数据类型还有object)，需要深度监听

```
data(){
    return {
        msg:'',
        status:[{name:'jack'}]
    }
},
watch{
    msg:function(newV, oldV) {
        console.log(newV, oldV);
    },
    status:{
        deep:true, // 深度监听
        handler:function(newV, oldV) {
            console.log(newV, oldV);
        }
    }
}
```

## Vue-计算属性-getter

* `computed`中监听的方法包含`get`和`set`方法，默认监听`get`方法。

同时监听两个数据及以上的更改：

* 实现点击切换歌曲
* 实现点击切换高亮选项(`currentIndex == index`)

```
<audio :src='getCurrentSongSrc' autoplay controls></audio>
<ul>
  <li v-for="(item, index) in musicData" @click="clickHandler(index)" :class='{active:currentIndex == index}'>
    <h2>{{ item.id}} - {{ item.name }}</h2>
  </li>
</ul>
```

```
var musicData = [
    { id:1, name:'xxx', songSrc=''},
    { id:1, name:'xxx', songSrc=''},
    { id:1, name:'xxx', songSrc=''},
    { id:1, name:'xxx', songSrc=''},
]
```

```
data(){
    return {
        musicData:musicData,
        currentIndex:0
    }
},
computed:{
    // 计算属性默认只有get方法
    getCurrentSongSrc:function() {
        return this.musicData[this.currentIndex].songSrc;
    }
},
methods:{
    clickHandler(index) {
        this.currentIndex = index;
    }
}
```

完整的`computed`中`get`方法书写方式：

```
computed:{
    getCurrentSongSrc:{
        get:function() {
             return this.musicData[this.currentIndex].songSrc;
        }
    }
},
```

## Vue-计算属性-setter

结合上面内容理解。

* 默认只监听`getCurrentSongSrc`方法中的`get`方法

* 当发生点击事件时，调用`set`方法，修改了`currentIndex`的值，从而使得监听的`get`方法有新的返回值

* `this.getCurrentSongSrc` 调用`get`方法
* `this.getCurrentSongSrc = index` 调用`set`方法

```
computed:{
    getCurrentSongSrc:{
        set:function(newV) {
            this.currentIndex = newV;
        }   
        get:function() {
             return this.musicData[this.currentIndex].songSrc;
        }
    }
},
methods:{
    clickHandler(index) {
        this.getCurrentSongSrc = index;
    }
}
```

## Vue-组件生命周期

* `beforeCreate`
* `created`
* `beforeMount`
* `mounted`
* `beforedUpdate`
* `updated`
* `actived`
* `deactived`
* `beforeDestory`
* `destoryed`
* `errorCaptured`

#### `beforeCreate`与`created`

`beforeCreate`：

* 组件创建之前
* `console.log(this.msg)` // undefine

`created`：

* 组件创建之后
* 使用该组件，就会调用`created`方法
* 在`created`这个方法中可以操作后端的数据
* 应用：发起ajax请求

#### `beforeMount`与`mounted`

`beforeMount`：

* 挂载数据到DOM之前会调用
* `console.log(document.getElementById('app'))` // 

`mounted`：

* 挂载数据到DOM之后会调用
* `console.log(document.getElementById('app'))` // 
* 操作DOM

#### `beforedUpdate`与`updated`

`beforedUpdate`：

* 页面DOM更新时调用
* 在更新DOM之前调用，
* 应用：可以获取原始的DOM
* `console.log(document.getElementById('app').innerHTML)`

`updated`：

* 页面DOM更新时调用
* 在更新DOM之后调用，
* 应用：可以获取最新的DOM
* `console.log(document.getElementById('app').innerHTML)`


#### `beforeDestory`与`destoryed`

`beforeDestory`：

* 销毁之前
* 组件的创建与销毁`v-if`

`destoryed`：

* 销毁之后

vue内置组件<keep-alive></keep-alive>能在组件的切换过程中将状态保留在内存中。防止DOM的重复渲染

* 当将组件放在`<keep-alive>`标签中，点击后将不再调用`destory`两个方法

```
data(){
    return {
        isShow:true
    }
},
template:'
    <keep-alive>
        <Test v-if="isShow" />
    </keep-alive>
    <!--点击时将isShow取反-->
    <button @click='isShow =!isShow'></button>
'
```

#### `actived`与`deactived`

`actived`：

* 组件被激活了

`deactived`：

* 组件被停用了

`<keep-alive>`：缓存机制

当将组件放在`<keep-alive>`标签中，点击后将不再调用`destory`两个方法,将会调用`active`两个方法。



