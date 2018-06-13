![](https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1528256738564&di=5183176de0e204e9ebc3ca12f38798bb&imgtype=0&src=http%3A%2F%2Fs1.51cto.com%2Fwyfs01%2FM02%2F2F%2FE4%2FwKioOVJHl4byKGdKAAGJ3-SrnPw737.jpg)

## JS

基于事件和对象驱动，并具有安全性的脚本语言。

**语言规范**

```js
<script type="text/javascript"></script>
<script type="text/javascript" src="js文件"></script>
```

**大小写敏感**

**结束符号:** ;

**变量:** 内存中运行的最小单位。  
`var name = "tom";`  
**命名规则:** 字母、数字、下划线、$符号、汉字组成，不能以数字开头。

**数据类型:** 6种。    
`number(int/float)、string、boolean、null 、undefined、object(数组是对象的一部分)`  
**null类型:** 空对象。

**typeof:** 判断变量的数据类型。

```
var name = "tom";
console.log(typeof name);
```

**Number数值数据类型:**  
**最大数，最小数:**   
`Number.MAX_VALUE,Number.MIN_VALUE`  
**无穷大数:**  
`infinity。infiniti.英菲尼迪·日本车。`

**比较运算符:** 
 
```
===  全等于  数据类型和数值
!==  全不等于
```

**逻辑运算符:**  
注意:  
1.&&与||逻辑与和逻辑或在js里面最终返回的结果不是bool值true or false。  
返回的结果是最终影响结果的那个操作数。  
**逻辑非 ! 和比较运算符， 返回的结果是一个布尔值。**  
原则上逻辑运算符的左右两边是布尔数据，如果不是则进行数据类型转换。  
2.短路运算  
只给执行一个操作数，另一个操作数不执行，则称为另一个操作数被短路。

**流程控制:** 标志位 break 的使用。

**函数:**  
**关键字function**  
1.先调用后声明的前提条件是全部代码在同一个`<script>`标签中

```
function 函数名 () {
} // 有预加载过程，允许我们先调用再声明函数。预加载：先把函数声明
放进内存。表面上是先调用后加载，实际还是先声明后调用。
调用函数：函数名(); 
```

2.变量赋值方法声明(匿名函数)

```  
js里面一切皆对象  
var 变量名 = function () {}
调用函数：变量名();
```

**函数实参、形参的对应要求:**  
**PHP语法中:** 形参没有默认值的条件下，实参个数不能少于形参个数。       
**js中:** 形参和实参没有对应的要求。

**arguments关键字模仿方法重载:**  
**方法重载:** 名称一样的方法，参数的个数或类型不一样。PHP和JS中没有重载。  
在函数内部可以接受到传递进来的实参信息。  
数值下标方式，取得各实参信息arguments[0],arguments[1]。  
有length属性获得实参个数。  

**callee关键字:**  
函数内部被使用，代表当前函数的引用（名称）。  
降低代码的耦合度。 

``` 
function f1 () {
    // 使得本身函数发生调用
    f1();
    arguments.callee();
}
```

**阶层函数：**

```
function jieceng(n) {
    if (n == 1){
        return 1;
    }
    return n * jieceng(n -1);
}
```

**引用传递：多个变量指向同一个对象**  

```
// 函数是一个对象，引用传递。
var jc = jieceng; 
// 销毁jieceng函数对象
jieceng = null;
```   

**阶层函数变成:**

```js
<script type="text/javascript">

	function jieceng(n) {
		if (n == 1) {
			return 1;
		}
		return n * jc(n - 1);
	}

	var jc = jieceng;
	var jb = jieceng;
	jieceng = null;

	alert(jc(3));

</script>
```

**发现问题：** 

```js
var ja = jieceng;
var jb = jieceng;
```
阶层函数中的：`return n * jc(n -1);` 就不太合适。

**阶层函数最后变成：**

```js
<script type="text/javascript">

	function jieceng(n) {
		if (n == 1) {
			return 1;
		}
		return n * arguments.callee(n - 1);
	}

</script>
```

**函数返回值问题:**  
js中return还可以返回一个function函数。  
一切皆对象。    
嵌套函数。

```js
<script type="text/javascript">

	function f1 () {
		var name = "tom";

		function f2 () {
			console.log("i am function 2");
		}
		return f2;
	}

	var f = f1(); // 引用赋值，将f2赋值给f
	f();          // 调用

</script>
```

**函数调用：**

`传统方式：函数名称();`  

**匿名函数自调用：**  

```js
<script type="text/javascript">

	var show = function () {
		console.log("i am show function ");
	}
	// 传统方式
	show();

	// 匿名函数自调用
	(function (title) {
		console.log("i am function " + title);
	})('COOL');

</script>
```
  
**全局变量和局部变量：**  
**php:** 函数外部声明的变量是全局变量。函数内部也能声明全局变量使用global关键字描述 global $subject = "php";  
函数调用后执行。  
**js:** 函数内部声明全局变量 前不加var字段。  
函数调用后执行。

```js
<script type="text/javascript">

	function f1() {
		subject = "js";
	}
	f1();
	console.log(subject);

</script>
```

数组三种声明方式：

```js
<script type="text/javascript">

	var arr1 = [1, 2, 3];
	var arr2 = new Array(1, 2, 3);
	var arr3 = new Array();
	arr3[0] = 1;
	arr3[1] = 2;
	arr3['city'] = "hangzhou";
	console.log(arr1, arr2, arr3);
	console.log(arr3[0], arr3[1], arr3.city, arr3['city']);

</script>
```

数组长度：数组最大下标数 + 1  
like是成员属性，不是数组长度

```js
<script type="text/javascript">

	var animal1 = new Array('dog', 'pig', 'cat', 'duck');
	animal1[4] = "tiger";
	animal1['like'] = "beautiful";
	console.log(animal1.length);  // 5

	var animal2 = new Array('dog', 'pig', 'cat', 'duck');
	animal2[4] = "tiger";
	animal2[19] = "ele";
	console.log(animal2.length);  // 20

</script>
```

数组常用方法：

```js
<script type="text/javascript">

	var animal1 = new Array('dog', 'pig', 'cat', 'duck');
	/*
		push()    pop()
		unshift() shift()
		indexOf() lastIndexOf()
	*/

	console.log(animal1.indexOf('dog'));     // 0
	console.log(animal1.lastIndexOf('cat')); // 2

</script>
```

**字符串操作：**  

eval用法：  
eval(参数字符串)  
主要用于接收接口的字符串信息 变成实体信息  

```js
<script type="text/javascript">

	// eval("") 将内部参数字符串当成表达式 在上下文执行
	var a = 10;
	var b = 20;
	console.log(a + b);          // 30
	console.log("a + b");        // a + b
	console.log(eval("a + b"));  // 30
	console.log(alert(1));       // undefined
	console.log(eval(alert(1)))  // undefined

</script>
```

## DOM

**DOM 文档 对象 模型**    
**php：** php语言和xml/html标签之间的桥梁  
**js：** js语言和xml/html标签之间的桥梁  
文档节点、元素节点、文本节点、属性节点、注释节点 

**元素节点的获取：**   
1.document.getElementById(id属性值)  
2.document.getElementsByTagName(tag标签名称) 返回集合列表（数组）  
3.document.getElementsByName(name属性值) 不推荐使用  

```js
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<h2>获取元素节点</h2>
	<input type="text" name="name" id="username" value="Tom" /></br>
	<input type="text" name="mail" id="usermail" value="362190221@163.com" /></br>
	<input type="text" name="tele" id="usertele" value="13777899876" /></br>
	<input type="button" value="触发" onclick="f()">
</body>
</html>

<script type="text/javascript">
	// 1
	var usermail = document.getElementById('usermail');
	console.log(usermail);   // <input type="text" name="mail" id="usermail" value="362190221@163.com">

	// 2 返回集合对象HTMLCollection
	var inputs = document.getElementsByTagName('input');
	console.log(inputs);     // HTMLCollection(4) [input#username, input#usermail, input#usertele, input, username: input#username, name: input#username, usermail: input#usermail, mail: input#usermail, usertele: input#usertele, …]

	var h2 = document.getElementsByTagName('h2');
	console.log(h2);         // HTMLCollection [h2]
	console.log(h2[0]);      // <h2>获取元素节点</h2>
	console.log(h2.item(0)); // <h2>获取元素节点</h2>

	// 3 返回集合对象NodeList 不推荐使用
	var names = document.getElementsByName('name');
	console.log(names);      // NodeList [input#username]

</script>
```

**文本节点获取：** 

子节点获取：
firstChild / lastChild

```js
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<h2>获取子节点与文本节点</h2>
</body>
</html>

<script type="text/javascript">
	// 1 元素节点对象.firstChild/lastChild
	var textObj = document.getElementsByTagName('h2')[0];
	console.log(textObj.firstChild);           // "获取文本节点"

	// 通过文本节点，获取其对应的文本信息
	console.log(textObj.firstChild.nodeValue); // 获取文本节点

</script>
``` 

兄弟节点获取：  
nextSibling 获得下一个兄弟节点  
previousSibling 获得上一个兄弟节点  
childNodes 父节点获取内部全部的子节点信息  

```js
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<h2>获取子节点与兄弟节点</h2>
	<ul>
		<li>北京</li>
		<li>南京</li>
		<li>杭州</li>
	</ul>
</body>
</html>

<script type="text/javascript">
	// 1 元素节点对象.firstChild/lastChild
	var textObj = document.getElementsByTagName('h2')[0];
	console.log(textObj.firstChild);                      // "获取子节点与兄弟节点"
	// 通过文本节点，获取其对应的文本信息
	console.log(textObj.firstChild.nodeValue);            // 获取子节点与兄弟节点

	// 2 childNodes:父节点获取内部全部子节点信息
	var ul = document.getElementsByTagName('ul')[0];      // 具体元素节点对象
	console.log(ul.childNodes);                           // NodeList(7) [text, li, text, li, text, li, text]
	// 上一个、下一个兄弟节点
	var nanjing = document.getElementsByTagName('li')[1];
	console.log(nanjing);                                 // 节点对象 <li>南京</li>
	console.log(nanjing.firstChild);                      // 文本节点 "南京"
	console.log(nanjing.firstChild.nodeValue);            // 文本信息 南京
	// nextSibling previousSibling 会先找空白节点
	console.log(nanjing.previousSibling);                 // #text
	console.log(nanjing.previousSibling.previousSibling); // 北京 节点对象 <li>北京</li>
	console.log(nanjing.nextSibling.nextSibling);         // 杭州 节点对象 <li>杭州</li>
</script>
```

父节点获取：  
`节点.parentNode`

```js
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<h2>获取子节点与兄弟节点</h2>
	<ul>
		<li>北京</li>
		<li>南京</li>
		<li>杭州</li>
	</ul>
</body>
</html>

<script type="text/javascript">
	// node.parentNode
	var nanjing = document.getElementsByTagName('li')[1];
	console.log(nanjing.firstChild);  								   // 子节点 "南京"
	console.log(nanjing.parentNode);                                   // 父节点 <ul>...</ul>   
	console.log(nanjing.parentNode.parentNode);                        // 父节点 <body>...</body>
	console.log(nanjing.parentNode.parentNode.parentNode);             // 父节点 <html>...</html>
	console.log(nanjing.parentNode.parentNode.parentNode.parentNode);  // 父节点 #document
	
</script>
```

**属性操作：**  
属性值操作：

`1.node.属性`  
`2.getAttribute()`  
`3.setAttribute()`  

```js
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<h2>属性值操作</h2>
	<input type="text" name="name" id="username" value="Tom" class="input-name" weight="155"/></br>
	<input type="button" value="获取" onclick="getValue()">
	<input type="button" value="设置" onclick="setValue()">
</body>
</html>

<script type="text/javascript">
	
	var node = document.getElementsByTagName('input')[0];
	function getValue() {
		console.log(node.type);                   // text
		console.log(node.name);                   // name
		console.log(node.value);                  // Tom
		console.log(node.class);                  // undefined
		console.log(node.className);              // input-name
		console.log(node.weight);                 // undefined
		console.log(node.getAttribute('weight')); // 155
	}
	function setValue() {
		node.name = "Tomcat";
		node.className = "input-name-set";
		node.weight = "145";                      // 无效
		node.setAttribute('weight', '140');       // 有效
	}
	
</script>

```

属性节点操作：  

```
var attrList = 元素节点对象.attributes; 
返回对应节点内部所有属性节点信息 ，数组列表形式。

attrList.属性名称 // 获取具体属性节点
```

```js
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<h2>属性值操作</h2>
	<input type="text" name="name" id="username" value="Tom" class="input-name" weight="155"/></br>
</body>
</html>

<script type="text/javascript">
	
	var attributesList = document.getElementsByTagName('input')[0].attributes;
	console.log(attributesList);
	console.log(attributesList.type);            // 属性节点
	console.log(attributesList.name);            // 属性节点
	console.log(attributesList.value);           // 属性节点
	console.log(attributesList.value.nodeValue); // 文本节点
	
	/*
	*
	* 节点.nodeType:
	* 1 ---> 元素节点 
	* 2 ---> 属性节点
	* 3 ---> 文本节点
	* 4 ---> 文档节点
	*/
	console.log(attributesList.value.nodeType); // 2
</script>
```

**节点追加和创建：**  
节点创建：

```  
 元素节点：document.createElement(tag标签);
 文本节点：document.createTextNode(文本内容);
 属性设置：node.setAttribute(名称, 值);
```

节点追加：  

```
 父节点.appendChild(子节点)；
 父节点.insertBefore(newNode, oldNode)；// newNode放在oldNode前边
 父节点.replaceChild(newNode, oldNode); // newNode替换oldNode位置
```

```js
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<h2>节点创建和追加</h2>
</body>
</html>

<script type="text/javascript">
	/*
	* <ul>
	*	<li province="beijing">北京</li>
	*	<li province="jiangsu">南京</li>
	*   <li province="zhejiang">杭州</li>
	* </ul>
	*/
	var ul = document.createElement('ul');
	var provinces = ['beijing', 'jiangsu', 'zhejiang'];
	var citys = ['北京', '南京', '杭州'];
	for(var k in citys) {
		// 创建li的元素节点
		var li = document.createElement('li');
		// 创建li的文本节点
		var city = document.createTextNode(citys[k]);
		// 给li节点设置属性
		li.setAttribute('province', provinces[k]);
		// 给li节点追加文本节点关系
		li.appendChild(city);
		// 给ul节点追加li节点关系
		ul.appendChild(li);
	}
	// 给body追加ul节点
	var body = document.getElementsByTagName('body')[0];
	body.appendChild(ul);
</script>
```

```js
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<h2>节点创建和追加</h2>
	<ul>
		<li province="beijing">北京</li>
		<li province="jiangsu">南京</li>
	    <li province="zhejiang">杭州</li>
	</ul>
	<input type="button" value="追加" onclick="append()" />
</body>
</html>

<script type="text/javascript">

	// 追加<li province="guangzhou">广州</li>
	function append() {
		var ul = document.getElementsByTagName('ul')[0];
		// 创建li的元素节点
		var li = document.createElement('li');
		// 创建li的文本节点
		var city = document.createTextNode('广州');
		// 给li节点设置属性
		li.setAttribute('province', 'guangzhou');
		// 给li节点追加文本节点关系
		li.appendChild(city);
		// 给ul节点追加li节点关系
		ul.appendChild(li);
		ul.insertBefore(li, ul.firstChild);
	}

</script>
```

**节点复制和删除：**  

```
被复制的节点.cloneNode(true/false);
true:本身节点 + 内部节点
false:本身节点
```

```js
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<h2>节点复制和删除</h2>
	<ul id="south">
		<li province="jiangsu" id="js">南京</li>
	    <li province="zhejiang" id="zj">杭州</li>
	    <li province="guangzhou" id="gz">广州</li>
	</ul>
	<ul id="north">
		<li province="beijing" id="bj">北京</li>
	</ul>
	<input type="button" value="复制追加" onclick="copy()" />
	<input type="button" value="删除追加" onclick="delet()" />
</body>
</html>

<script type="text/javascript">

	function copy() {
		var bj = document.getElementById('bj');
		var north = document.getElementById('north');
		var copyBj = bj.cloneNode(true);
		north.appendChild(copyBj);
	}

	function delet() {
		var lies = document.getElementsByTagName('li');
		var length = lies.length;
		if (length == 4) {
			return;
		}
		var li = document.getElementsByTagName('li')[length - 1];
		li.parentNode.removeChild(li);
	}

</script>
```

**dom对css样式操作：**  

```
1.获取样式  
元素节点.style.css样式名称  
divnode.style.width;  

2.设置css样式（有则修改，没有则添加）  
元素节点.style.css样式名称 = 值;  
divnode.style.width = “500px”;

注意：
1.dom操作操作只能操作“行内样式” (css样式分为行内 内部 外部)
2.js变量命名不予许有中横杆。
3.修改样式有则修改 无则添加 修改完后都成为行内样式。
```

TODO...

## 事件操作