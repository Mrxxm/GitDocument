![](https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1528258932371&di=36afa50affe4d24aee5cc4aa138bc12b&imgtype=0&src=http%3A%2F%2Fi-2.497.com%2F2015%2F12%2F25%2F1f13c552-7b77-43cb-bb81-1fb018bd61b4.jpg)

# Java语言基础
---

**操作系统:** 是计算机程序，管理和控制计算机硬件和软件。  
用户 -> 软件 -> 操作系统 -> 硬件

**平台:** 系统就是平台。支持程序运行硬件和软件环境称为平台。
  
**平台的相关性:** 不同平台有不同的指令。

**原码、反码、补码:** 反码 符号位相反。补码 在反码的基础上最后一位加一。  
正数的原码、反码、补码都相同。  
负数的原码  10000101  
负数的反码  11111010  
负数的补码  11111011   

**跨平台性:** 游戏代码 -> 游戏包 -> 游戏模拟器 -> 不同平台  
跨平台性：游戏代码 -> 字节码 -> java虚拟机JVM -> 不同平台  
*(编译工具：javac) (启动JVM的工具：java)*   

**JDK、JRE 、JVM:** JDK包含JRE包含JVM  
JDK：java开发工具  
JRE：java运行环境  

**目录:**  
bin：java操作工具，javac、启动JVM的工具java  
db：存放java测试的数据库derby  
include：存放c++的头文件  
jre：java的运行环境里面有JVM  
lib：java运行核心库  
src：java的源代码  

```java
class java
{
	public static void main(String[] args)
	{
		System.out.println("Hello world!");
	}
}
```

**java编译运行机制:**  编译性 解释性语言  

**环境变量:**  
环境变量名 *JAVA_HOME* 指jdk安装的根目录  
环境变量名 *PATH* 指Java开发工具位置，如\jdk1.8.0\bin  
环境变量名 *CLASSPATH* 指JVM在运行时去哪一个路径去加载字节码文件  

编译：
`javac -d classes 1.java`

运行：
`java -classpath classes Hello`


**基本语法:**  
1.严格区分大小写。   
2.一定要有主方法 程序入口。    
3.可以定义多个类，但只能有一个类被定义成public类。  
4.一个源文件包含多个类，编译成功产生多个.class文件。  
   


**源文件包含public类，源文件必须与该public类同名:**

```java
public class java
{
	public static void main(String[] args)
	{
		System.out.println("Hello world!");
	}
}

```

**注释:** 单行、多行、文档注释

**关键字:**

**保留字:**

**分割符:**     

**标识符：**

</br>
</br>

----

**字面量和常量:**

**变量分类作用域:**  
成员变量/字段：定义在花括号内，方法之外。定义成员变量使用static修饰。  

```java
class java
{
	int age = 10;
	public static void main(String[] args)
	{
		System.out.println(age);
	}
}
```

改为 static 修饰 age 变量。


**局部变量:** 方法内的变量

**java表达式:**

数据类型和分类：  
基本类型/引用数据类型：byte、short、int 、long、float、double、char、boolean || 类、接口、数组


| 基本类别  | 大小  | 最小值 |   最大值 |
|:------------- |:---------------:| -------------:| :------ |
| boolean       |               |         |       |
| char          | 16 bit        |     unicode 0 | unicode 2^16-1 |
| byte          | 8  bit        |          -128 | -127           |
| short         | 16 bit        |         -2^15 | +2^15-1        |
| int           | 32 bit        |         -2^31 | +2^31-1        |
| long          | 64 bit        |         -2^63 | +2^63-1        |
| float         | 32 bit        |       IEEE754 | IEEE754        |
| double        | 64 bit        |       IEEE754 | IEEE754        |



**数据过大和溢出:** int 最大值2147483647  

```
int intMax = 2147483647;  
intMax = intMax + 1;   
intMax = -2147483648; 
``` 

分析二进制  
0b0111 1111 1111 1111 1111 1111 1111 1111  
0b0000 0000 0000 0000 0000 0000 0000 0001  
0b1000 0000 0000 0000 0000 0000 0000 0000 = -2147483648 

**基本数据类型的转换:** 自动类型转换/强制类型转换  
![](https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1528024696618&di=aa0026e42caaa358d23d563dbfe91ea1&imgtype=0&src=http%3A%2F%2Fimage.bubuko.com%2Finfo%2F201804%2F20180415103312146279.png)  


`System.out.println(‘A’ + 1);`   
‘A’是char类型的自动转换为int类型，所以输出值为66；

**NaN:** 自己都不等于自己

**++、—:** ++a、a++ 都是先自身加一。++a传递运算之后的值，a++ 先传递a原始值。

**分页业务逻辑:**

```java
totalCount = 46；
pageSize = 10；
int totalPage = totalCount % pageSize == 0 ? totalCount / pageSize : totalCount / pageSize + 1;
// 上一页
int currentPage = 4;
int prevPage = currentPage - 1;
int prevPage = currentPage - 1 > 1 ? currentPage -1 : 1;
```

移位操作：54

</br>
</br>


---

**输出矩形**

```java
class java
{
	public static void main(String[] args)
	{
		for (int i = 0; i < 8; i++){
			for (int j = 0; j < 8; j++){
				System.out.print("*");
			}
			System.out.println();
		}		
	}
}

```

**输出三角形**

```java
class java
{
	public static void main(String[] args)
	{
		for (int i = 0; i < 8; i++){
			for (int j = 0; j < i; j++){
				System.out.print("*");
			}
			System.out.println();
		}		
	}
}

```


**九九乘法表**

```java
class java
{
	public static void main(String[] args)
	{
		// 第一步
		// System.out.println("1 * 1 = 1");
		// System.out.println("1 * 2 = 2 2 * 2 = 4");

		// 第二步
		// int line = 3;
		// for (int i = 1; i <= line; i++){
		// 	System.out.print(i + " * " + line + " = " + (i * line) + " ");	
		// }

		// 第三步
		for (int line = 1; line <= 9; line++) {
			for (int i = 1; i <= line; i++) {
				System.out.print(i + " * " + line + " = " + (i * line) + "\t");
			}
			System.out.println();
		}
	}
}

```



**控制外层循环:** break结束当前循环。

```java
class java
{
	public static void main(String[] args)
	{
		outter:for (int line = 1; line <= 9; line++) {
			for (int i = 1; i <= line; i++) {
				if (line == 5) {
					break outter;
				}
				System.out.print(i + " * " + line + " = " + (i * line) + "\t");
			}
			System.out.println();
		}
	}
}

```
</br>
</br>


---


**Method(方法)、Function(函数):** 函数可以独立存在的，而方法是要依赖于对象的。本质都表示一种功能。

**方法的格式:** 

``` 
[修饰符] 返回值的类型 方法名（[参数]）
{
	// 方法体
}
```

**方法的调用:** 方法用static修饰、不用static修饰。static修饰的方法，用类名调用；不用static修饰的方法，用方法所在类的对象调用。main方法由JVM来调用。

**方法术语:**  
修饰符：public、static等；  
static修饰符：表示方法属于类，直接使用类名调用即可。  
返回值类型：方法完成一个功能，方法完成是否给调用者返回一个结果。
如果不需要给调用者返回一个结果，则使用void关键字。
                      
**方法调用者**  
方法的名称：遵循标示符的规范，使用动词表示，首字母小写。若是多个单词组成，则用驼峰表示法，*(之后的每一个单词的首字母大写)*。  
形式参数：方法圆括号中的变量，仅仅只是占位而已，参数的名称无所谓，形式参数可以有多个。  
参数列表：参数的类型 + 参数个数 + 参数顺序  
方法签名：方法的名称 + 方法的参数列表；在同一个类中方法签名是唯一的。  
方法体：方法花括号中的代码，表示具体完成该功能的代码。  
返回值：在方法体中，使用return关键字，功能一：给调用者返回一个结果值，此时方法不能用void修饰。功能二：结束当前方法。  
实际参数：调用者在调用方法时，实际传递的参数值。


**方法重载设计:** 两同一不同。同一个类中，方法名相同；参数不同；

**方法递归/斐波拉切数列/汉诺塔问题:**  
斐波拉切数列格式：F(0) = 0； F(1) = 1; F(n) = F(n -1) + F(n -2) (n >= 3, n=N+) 求F(5)的值

```java
class java
{
	public static void main(String[] args)
	{
		int result = java.F(5);
		System.out.print(result);
	}

	static int F(int n)
	{
		if (n == 0){
			return 0;
		} else if (n == 1){
			return 1;
		} else {
			return F(n - 1) + F(n - 2);
		}
	}
}

```

**JVM内存模型:**  
![](https://ss1.bdstatic.com/70cFuXSh_Q1YnxGkpoWK1HF6hhy/it/u=1099203330,1834774787&fm=27&gp=0.jpg)

本地方法栈：native修饰的方法：调用实现c或c++编写的方法，用java来实现。  
堆：Heap； 用new关键字，就表示在堆中开辟一块内存空间。  
栈：Stack； 存放方法的数据信息；  
方法区：字节码加载到内存中，就是加载到方法区。  

**GC:** 垃圾回收器。自动回收堆空间的内存。栈空间的内存随着方法的执行结束就自动释放。  

**数组定义:** 一旦初始化完成，数组的长度就固定了。  
**数组的静态初始化:**  
    特点：由程序猿自己，设置数组元素的初始值，数组的长度由系统决定。  
    语法：数组元素类型[] 数组名称 = new 数组元素类型[]{元素1，元素2，元素3}；  
**数组的动态初始化:**  
    语法：数组元素类型[] 数组名称 = new 数组元素类型[5];

**不同数据类型的初始值:**

**数组常见异常:**

**空指针异常:**

**索引越界:**

**打印数组元素:**

```java
// int类型数组String类型数组打印（函数重载）
public class ArrayPrint {
	public static void main(String[] args){
		int[] array1 = new int[]{1,2,3,4,5};
		String[] array2 = new String[]{"A","B","C","D","E"};
		ArrayPrint.print(array1);
		ArrayPrint.print(array2);// 函数重载
	}
	
	static void print(int[] arr){
		String ret = "[";
		for(int i = 0; i < arr.length; i++){
			ret = ret + arr[i];
			
			if(i != arr.length - 1){
				ret = ret + ",";
			}
		}
		ret = ret + "]";
		System.out.println(ret);
	}
	
	static void print(String[] arr){
		String ret = "[";
		for(int i = 0; i < arr.length; i++){
			ret = ret + arr[i];
			
			if(i != arr.length - 1){
				ret = ret + ",";
			}
		}
		ret = ret + "]";
		System.out.println(ret);
	}
}
```

**逆序排列数组元素**

```java
// 数组逆序排列
public class ArrayReverse {
	public static void main(String[] args){
		int[] array1 = new int[]{9,8,7,6,5,4,3,2,1,0};
		System.out.println(array1.length);
		ArrayReverse.print(reverse(array1));
	}
	
	static int[] reverse(int[] arr){ // 可以返回类型为一个数组int[]
		int[] array2 = new int[arr.length];
		for(int i = arr.length - 1; i >= 0; i--){ // 数组索引与数组长度要十分注意，容易引起索引越界异常
			// 0 9
			// 1 8
			// 2 7
			array2[arr.length - 1 - i] = arr[i];
		}
		return array2;
	}
	
	static void print(int[] arr){
		String ret = "[";
		for(int i = 0; i < arr.length; i++){
			ret = ret + arr[i];
			
			if(i != arr.length - 1){
				ret = ret + ",";
			}
		}
		ret = ret + "]";
		System.out.println(ret);
	}
}
```
</br>
</br>

-------


**mian方法中的数组参数:** main方法的String数组参数，其实是暴露给程序运行者的，用于给程序传递一个数据信息。

```java
class java
{
	public static void main(String[] args)
	{
		System.out.println(args.length);
	}
}
```
`
javac java.java
`  

`java java 1 1 1
` 
 
`
结果输出：3
`  


**方法参数值传递基本数据类型:**

```java
class java
{
	public static void main(String[] args)
	{
		int x = 10;
		System.out.println("x = " + x);
		change(x);
		System.out.println("x = " + x);
	}

	static void change(int x)
	{
		System.out.println("x = " + x);
		x = 50;
		System.out.println("x = " + x);
	}

	/**
	* x = 10 
	* x = 10 
	* x = 50
	* x = 10
	*/
}

```

**方法参数值传递引用数据类型:** 基本数据类型传递的是，基本数据值的副本。引用数据类型传递的是，引用类型的地址值的副本。

**二维数组初始化:** 静态初始化，动态初始化。

**java中的foreach:**

```java
class java
{
	public static void main(String[] args)
	{
		int[] age = new int[]{1, 2, 3};
		for (int ele : age) {
			System.out.print(ele);
		}
	}
}
/**
* 1 2 3
*/

```


**方法的可变参数:**

```java
// 数组可变参数求和
public class ArrayVarArgsGetSum {
	public static void main(String[] args){
		int[] array1 = new int[]{1,2,3,4,5,6,7,8,9};
		int SUM1 = ArrayVarArgsGetSum.getSum(array1);
		System.out.println("array1 of sum:" + SUM1);
		
		int SUM2 = ArrayVarArgsGetSum.getsum(1,2,3,4,5,6,7,8,9,10);
		System.out.println("VarArgs of sum:" + SUM2);
	}
	
	static int getSum(int[] arr){
		int sum = 0;
		for(int ele : arr){
			sum = sum + ele;
		}
		return sum;
	}
	
	// 可变参数使用
	static int getsum(int ... array){
		int sum = 0;
		for(int i = 0; i < array.length; i++){
			sum = sum + array[i];
		}
		return sum;
	}	
}
```


**数组相关算法:**

**数组复制:**

```java
// 数组复制
public class ArrayCopy {
	public static void main(String[] args){
		int[] srcArray = new int[]{5,2,1};
		int[] destArray = new int[srcArray.length];
		ArrayCopy.print(destArray);
		ArrayCopy.copy(srcArray,0,destArray,0,srcArray.length);
		ArrayCopy.print(destArray);
	}
	
	static void print(int[] arr){
		String ret = "[";
		for(int i = 0; i < arr.length; i++){
			ret = ret + arr[i];
			
			if(i != arr.length - 1){
				ret = ret + ",";
			}
		}
		ret = ret + "]";
		System.out.println(ret);
	}
	
	// 数组复制
	static int[] copy(int[] sArr, int s, int[] dArr, int d, int l){
		for(int i = 0; i < l; i++){
			dArr[d + i] = sArr[s + i];
		}
		return dArr;
	}
}
```

**冒泡排序:** 依次找到最大数值放在数组最后一位。循环一次比较少一次。
思路

```java
// 冒泡排序
public class MethodBubbleSort {
	public static void main(String[] args){
		int[] array1 = new int[]{4,2,6,7,5,3};
		MethodBubbleSort.print(array1);
		MethodBubbleSort.sort(array1);
		MethodBubbleSort.print(array1);
	}
	
	// 要交换length - 1次
	static int[] sort(int[] arr){
		/*
		// 第一次交换得出最大的数排在数组末尾
		for(int i = 1; i <= arr.length - 1; i++){
			if(arr[i -1] > arr[i]){
				swap(arr, i - 1 , i);
			}
		}
		// 第二次
		for(int i = 1; i <= arr.length - 2; i++){
			if(arr[i - 1] > arr[i]){
				swap(arr, i - 1, i);
			}
		}
		// 第三次
		for(int i = 1; i <= arr.length - 3; i++){
			if(arr[i - 1] > arr[i]){
				swap(arr,i - 1, i);
			}
		}
		return arr;
		*/
		
		// 整合规律
		for(int i = 1; i <= arr.length - 1; i++){
			for(int j = 1; j <= arr.length - i; j++){
				if(arr[j - 1] > arr[j]){
					swap(arr,j - 1, j);
				}
			}
		}
		return arr;	
	}
	
	
	static void swap(int[] arr, int index1, int index2){
		int temp = 0;
		temp = arr[index1];
		arr[index1] = arr[index2];
		arr[index2] = temp;
	}
	
	static void print(int[] arr){
		String ret = "[";
		for(int i = 0; i < arr.length; i++){
			ret = ret + arr[i];
			
			if(i != arr.length - 1){
				ret = ret + ",";
			}
		}
		ret = ret + "]";
		System.out.println(ret);
	}
}
```



**选择排序:** 依次选择比较，比较位比其他位置上的数值大，则交换。这样比较位将会留下最小值。

```java
// 选择排序
public class MethodSelectionSort {
	public static void main(String[] args){
		int[] array1 = new int[]{4,2,1,6,7,5,3};
		MethodSelectionSort.print(array1);
		MethodSelectionSort.select(array1);
		MethodSelectionSort.print(array1);
	}
	
	static void select(int[] arr){
		/*
		// 1
		for(int i = 1; i <= arr.length - 1; i++){
			if(arr[0] > arr[i]){
				swap(arr, 0 , i);
			}
		}
		// 2
		for(int i = 2; i <= arr.length - 1; i++){
			if(arr[1] > arr[i]){
				swap(arr, 1, i);
			}
		}
		*/
		
		// 规律 注意点：比较次数是length - 1；选择排序是将最小值放在首位；
		for(int i = 1; i <= arr.length - 1; i++){
			for(int j = i; j <= arr.length - 1; j++){
				if(arr[i-1] > arr[j]){ // 注意点：这个循环中的arr[i - 1]，不是arr[j - 1];特别注意。
					swap(arr, i-1, j);
				}
			}
		}
	}
	
	static void print(int[] arr){
		String ret = "[";
		for(int i = 0; i < arr.length; i++){
			ret = ret + arr[i];
			
			if(i != arr.length - 1){
				ret = ret + ",";
			}
		}
		ret = ret + "]";
		System.out.println(ret);
	}
	
	static void swap(int[] arr, int index1, int index2){
		int temp = 0;
		temp = arr[index1];
		arr[index1] = arr[index2];
		arr[index2] = temp;
	}
}
```



**折半查找:** 前提数组必须有序

```java
// 二分搜索法:前提--数组必须是有序的
public class MethodBinarySeacher {
	public static void main(String[] args){
		int[] array1 = new int[]{1,2,3,9,8,7,4,5,6};
		MethodBinarySeacher.bubbleSort(array1);
		MethodBinarySeacher.print(array1);
		
		int index = MethodBinarySeacher.binarySeacher(array1, 0);
		System.out.println(index);
	}
	
	static int binarySeacher(int[] arr, int key){
		int low = 0;//索引
		int high = arr.length - 1; //索引
		while(low <= high){ // 等号很关键，就是程序的最后出口
			int mid = (low + high) / 2;
			int midVal = arr[mid];
			if(midVal < key){// 猜小
				low = mid + 1;
			}else if(midVal > key){// 猜大
				high = mid - 1;
			}else{
				return mid;
			}	
		}
		return -1;
	}
	
	static void bubbleSort(int[] arr){
		for(int i = 1; i <= arr.length - 1; i++){
			for(int j = 1; j <= arr.length - i; j++){
				if(arr[j - 1] > arr[j]){
					swap(arr, j - 1, j);
				}
			}
		}
	}
	
	static void print(int[] arr){
		String ret = "[";
		for(int i = 0; i < arr.length; i++){
			ret = ret + arr[i];
			
			if(i != arr.length - 1){
				ret = ret + ",";
			}
		}
		ret = ret + "]";
		System.out.println(ret);
	}
	
	static void swap(int[] arr, int i, int j){
		int temp = 0;
		temp = arr[i];
		arr[i] = arr[j];
		arr[j] = temp;
	}
}
```


**杨辉三角:**
第一行循环的值，会影响下一行。

```java
class java
{
	public static void main(String[] args)
	{
		int[] a = new int[20];
		int pre = 1;
		for (int line = 1; line <= 10; line++) {
			for (int i = 0; i < line; i++) {
				int current = a[i];
				a[i] = current + pre;
				pre = current;
				System.out.print(a[i] + " ");
			}
			System.out.println();
		}
	}

	// 与数值交换很相似：帮助记忆
	static void swap(int[] arr, int index1, int index2)
	{
		int temp = arr[index1];
		arr[index1] = arr[index2];
		arr[index2] = temp;
	}
}
```


