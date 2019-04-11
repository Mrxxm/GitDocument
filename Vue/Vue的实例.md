## Vue实例
一个创建Vue的实例可以传入什么：(常用)
1. 挂载元素：el（此Vue实例是挂在与哪一个DOM节点下）



```js
el: '#app';
el: document.getElementById('app');
```

2. 数据：data Vue实例的数据对象 只会在created之前执行一次

* data的数据是在beforeCreate之后，created之前生成的。在这里的数据一般都能被监听到，如果数据格式在这里没有被定义，那么就需要deep watch了。


```js
    data: Object
```

3. 计算属性: computed

* 这里一般可以对数据做非空校验或者初始化一些跟随数据变化但是不能手动操作的数据。

```js
    computed: {
        reserveMessage: function() {
            return this.message.split('').reverse().join('');
        }
    }
```

4. 方法: methods: { key : Function } , 为实例添加自定义的方法，方法可用于指令表达式中, 方法内的this会指向Vue实例，Function不能使用箭头函数

```js
var vm = new Vue({
   data: {
       a : 1
   } ,
   methods: {
       add: function() {
           this.a++;
       }
       
       //或者
       add() {
       	this.a++;
       }
   }
});
```

5. watch:
一个对象，键是需要观察的表达式，值是对应回调函数。值也可以是方法名，或者包含选项的对象。

>注意：健值是对象时，一般用于深度监听，可以监听到对象内对象的值的变化，相反，普通的监听则不行


```js
new Vue({
    data: {
        a: 1,
        b: 2,
        obj: {
            info: {
                a: 1,
                b: 2
            }
        }
    },
    watch: {
        'a' : function(new.old) {
            console.log(new,old);
        },
        'obj': 'show',
        'obj': {
            hanlder: function(new,old) {
                console.log(new.info.a);
            },
            deep: true
        }
    },
    methods: {
        show: function(new,old) {
            console.log(new);
        }
    }
})
```

7. 过滤器：filters : Object, 作用域mustache语法内，也就是{{}}内

* 在项目中一般放在 marketing/frontend/src/Filter/index.js中 

```html
  <div id='app'>
      {{ message | filterA }}
  </div>
```

```js
new Vue({
    el: '#app',
    data: {
        message: 'Hello'
    },
    filters: {
        filterA: function(val) {
            return val + 'Vue.js!';
        }
    }
})
// result:
Hello Vue.js!
```

8. **生命周期钩子** ： 一个实例从生到死的过程

方法名 | 描述 | 特征
---|---|---
beforeCreate | Vue(组件)实例刚被创建。在属性值，方法被处理前， | this还未指向实例；适合给出loading效果/一些无关组件内容的请求也可以在这里发送
created | Vue(组件)实例创建完成，属性，方法已绑定 | this指向实例；数据绑定，计算属性，方法，watcher/事件回调等已建立
beforeMount | 模板编译/挂载前 |
mounted | 模板编译/挂载之后 | 这时候dom元素和data都能获取，相当于onload()
beforeUpdate | Vue(组件)实例更新之前 | 数据更新时调用，发生在虚拟 DOM 重新渲染和打补丁之前。
updated | Vue(组件)实例更新之后 |
beforeDestoyed | Vue(组件)实例销毁前 |
destroyed | Vue(组件)实例已销毁 | 只是把实例与DOM解绑了，并不是销毁了,解绑了意味着无法再通过实例来响应对应的DOM节点了

> 扩展
    1. [activated](https://cn.vuejs.org/v2/api/#activated)
    2. [deactivated](https://cn.vuejs.org/v2/api/#deactivated)



## Vue常用的内部命令（模板语法）

1. v-bind: 用来更新html属性 例如：v-bind:title 可简写为:title

	* 特殊一点的 :key 可以在key发生改变后重新初始化这个标签/组件

2. v-text: 用于更新绑定元素中的内容，类似于jQuery的text()方法
3. v-html ： 更新元素的 innerHTML,如果内容由用户决定，则不推荐使用
4. v-if：
5. v-else
6. v-else-if
7. v-for : 循环
8. v-once : 只渲染元素或组件一次，加上后内部的值不会随之改动
9. ==v-on==: 事件绑定,例如：v-on:click ,可简写为@click
10. v-show

### 修饰符

* 点操作符可以连续使用

#### 事件修饰符

Vue.js 为 v-on 提供了 事件修饰符。通过由点(.)表示的指令后缀来调用修饰符。

* .stop  阻止冒泡
* .prevent  取消默认事件
* .capture  事件捕获
* .self  当触发元素为本身的时候才会触发（非子元素）

#### 按键修饰符

可以用<input @keyup.enter.tab="handler" />表示绑定keyup事件且在回车时触发handler。

* .enter
* .tab
* .delete (捕获 “删除” 和 “退格” 键)
* .esc
* .space
* .up
* .down
* .left
* .right


#### model修饰符

使用方法 <input v-model.lazy.number="msg" > 234

	data() {
		return {
			msg: 123,
		}
	}

* .lazy  在改变后才触发（也就是说只有光标离开input输入框的时候值才会改变）对应change事件
* .number  将输出字符串转为Number类型·（虽然type类型定义了是number类型，但是如果输入字符串，输出的是string）
* trim  自动过滤用户输入的首尾空格


### 组件之间传递数据

1. 父组件传递给子组件

父组件

```html
  <template>
      <test :data="message"></test>
  </template>
```

```js
export default{
    components:{
    	test
    },
    data: {
        message: 'Hello'
    }
}
```

子组件

```html
  <template>
      <div v-html="data"></div>
  </template>
```

```js
export default{
    props: ['data'],
    data: {
    },
    //如果父组件需要请求或者不是立马能得出结果的情况，可以用computed返回props的方法获取到最新数据，computed内的数据如果不在template里面或v-if为false，则不会触发
    computed: {
    	dataAjax() {
    		return this.data;
    	}
    }
}
```

2. 路由传递


```js
//params传递 数据不会出现在地址栏上
this.$router.push({
	name:"组件名称",
	params:{
	  //需要的参数
	},
});

//获取方式
this.$route.params.???


//query传递 地址栏上会显示出数据
this.$router.push({
	name: '组件名称',
	query: {
	  //需要的参数
	}
});

//获取方式
this.$route.query.???
```

3. 子组件传递父组件

子组件

```html
  <template>
      <div @click="emit"></div>
  </template>
```

```js
export default{
    data: {
    	message: '你好'
    },
    methods: {
    	emit() {
    		this.$emit('hello', this.message);
    	}
    }
}
```

父组件

```html
  <template>
      <test :data="message" @hello="helloHandle"></test>
  </template>
```

```js
export default{
    components:{
    	test
    },
    data: {
    },
    methods: {
    	helloHandle(data) {
    		concole.log(data);
    		//你好
    	}
    }
}
```


### 一些坑

#### 对象拷贝问题

```js
let obj = {
	a:1,
	b:2,
	c:3
}
let test = obj;
test.a = 2;
console.log(obj);
//{a:2,b:2,c:3}

```

如果要深层拷贝的话
因为项目内有es6编译，可以直接用用let test = Object.assign({}, obj);

#### 样式问题

组件内的样式标签一般都是

	<style lang="less" scoped></style>
	
scoped表示当前样式只作用于当前组件，不会影响到别的文件。所以如果特殊情况要写一部分组件外的样式，可以把scoped去掉，不推荐。。因为去掉后样式作用于全局，可能会出现样式污染。

#### element ui 中的validate() 会因为rule中方法的原因而产生延迟。

比如rule中有一个api请求的话，validate方法也会变成异步操作。

#### npm run dev时发现 

	This is probably not a problem with npm. There is likely additional logging output above.
	
上面的问题是当前server端口已经被占用导致，，可以把当前端口（默认8080）kill掉，就可以正常运行了




### 其他参考文档

* 路由相关 <a href="https://router.vuejs.org/zh-cn/essentials/getting-started.html">vue-router</a>
* ui组件相关 <a href="http://element.eleme.io/#/zh-CN/component/quickstart">element UI</a>
* vue相关 <a href="https://cn.vuejs.org/v2/api/">vue api</a>
	

