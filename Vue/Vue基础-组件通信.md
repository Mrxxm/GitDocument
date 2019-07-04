## 补充组件通信

#### 通信二-父子多层嵌套通信

多层嵌套组件通信父与子之间

* `&attrs`
* `$listeners`

父向子通信：

* 父组件

```
data(){
    return {
        msg:'我是父组件的内容',
        messagec:'hello c'
    }
},
template:'
    <div>
        <p>这是一个父组件</p>
        <A :messagec='messagec' v-on:getCData="getCData"></A>
    </div>
',
methods:{
    // 执行C组件触发的函数
    getCData(val){
        console.log(val);
    }
}
```

A组件：

```
template:'
    <div>
        <B v-bind="$attrs" v-on="$listeners"></B>
    </div>
'
```

B组件：

```
template:'
    <div>
        <C v-bind="$attrs" v-on="$listeners"></C>
    </div>
'
```


子向父通信：

* C组件

```
template:'
    <div>
        <div @click='cClickHandler'>{{ $attrs.messagec }}</div>
    </div>
',
method:{
    cClickHandler(){
        this.$emit('getCData', ’我是C的数据‘);
    }
}
```

#### 通信三-中央事件总线

新建一个`Vue`事件`bus`对象，然后通过`bus.$emit`触发事件，`bus.$on`监听触发的事件。

* `$on`和`$emit`绑定同一个实例化对象

```
var bus = new Vue();

Vue.component('bro2', {
    data(){
        return {
            msg:'hello bro1'
        }
    },
    template:'
        <div>
            <p>我是老大</p>
            <input type="text" v-model="msg" @input="passData(msg)" />
        </div>
    '
    methods:{
        passData(val){
            // 触发全局事件globalEvent
            bus.$emit('globalEvent', val);
        }
    }
});

Vue.component('bro1', {
    data(){
        return {
            bro2Msg:''
        }
    },
    template:'
        <div>
            <p>我是老二</p>
            <p>老大传过来的数据:{{ bro2Msg }}</p>
        </div>
    ',
    mounted() {
        // 绑定全局事件globalEvent事件
        bus.$on('globalEvent', (val)=>{
            this.bro2Msg = val;
        })
    }
});

var App = {
    data() {
        return {
            msg:'我是父组件的内容'
        }
    },
    template:'
        <div>
            <bro1></bro1>
            <bro2></bro2>
        </div>
    '
}
```

#### 通信四-父子组件通信

* `provide`：提供变量

* `inject`：注入变量

* 不同于`prop`属性，只能从当前父组件中获取数据

```
Vue.component('Child', {
    data() {
        return {
            msg:''
        }
    },
    template:'
        <div>
            我是子组件{{ msg }}
        </div>
    ',
    inject:['for'],
    created(){
        this.msg = this.for;
    }
});

Vue.component('Parent', {
    template:'
        <div>
            <p>我是父组件</p>
            <Child />
        </div>
    ',
});

var App = {
    data() {
        return {
            
        }
    },
    provide:{
        for:'入口组件'
    },
    template:'
        <div>
            <h2>我是入口组件</h2>
            <Parent />
        </div>
    '
}

new Vue({
    el:"#app",
    template:'<App />',
    data(){
        return {
            
        }
    },
    components:{
        App
    }
})
```

#### 通信五-父子组件通信

点击按钮将值传入子组件中，当传入数值发生变化，触发`@change`事件。

* `$parent`

* `$children`

* `@change`事件在值发现变化时，触发

```
Vue.component('Child', {
    props:{
        // v-model会自动传递一个字段为value的prop属性
        value:String, 
    },
    data() {
        return {
            mymessage:this.value
        }
    },
    methods:{
        changeValue(){
            this.$parent.message = this.mymessage;
        }
    },
    template:'
        <div>
            <input type="text" v-model="mymessage" @change="changeValue">
        </div>
    '
});

Vue.component('Parent', {
   data() {
        return {
            message: 'hello'
        }
   },
   template:'
        <div>
            <p>我是父组件{{ message }}</p>
            <button @click="changeChildValue">test</button>
            <Child></Child>
        </div>
   ',
   methods:{
        changeChildValue() {
            this.$children[0].mymessage = 'hello';
        }
   }
})
```

