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
  