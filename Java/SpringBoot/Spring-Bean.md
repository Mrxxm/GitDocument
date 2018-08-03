# Spring-Bean

## Bean的配置项

理论上只有class是必须的

* Id (唯一标识)
* Class (具体实例化的类)
* Scope (范围，作用域)
* Constructor arguments (注入方式-构造器参数)
* properties (注入方式-属性)
* Autowiring mode (自动装配模式)
* Lazy-initialization mode (懒加载模式)
* Initialization/destruction method (初始化/销毁方法)

## Bean的作用域

* singleton (单例：一个Bean容器中指存一份[一个Bean容器：new ClassPathXmlApplicationContext(springXmlpath.split("[,\\s]+"));])
* prototype (每一次请求(每次使用)创建新的实例，destroy方法不生效)
* request (每次http请求都会创建一个实例且在当前request内有效)
* session (同上，在当前session内有效)
* global session (基于portlet的web中有效(portlet定义global session)，如果在web中，同session)

#### 实例一(singleton)

```java
package com.bean;

public class BeanScope {
	public void say() {
		System.out.println("BeanScope say:" + this.hashCode());
	}
}
```

```xml
<beans>
	<bean id="beanScope" class="com.bean.BeanScope" scope="singleton"> </bean>
</beans>
```

单元测试

```java
@Test
public void testSay() {
	BeanScope beanScope = super.getBean("beanScope");
	beanScope.say();
	
	BeanScope beanScope2 = super.getBean("beanScope");
	beanScope2.say();
}
```

输出 

```
BeanScope say: 1443156414
BeanScope say: 1443156414
```

#### 实例二(prototype)

```java
package com.bean;

public class BeanScope {
	public void say() {
		System.out.println("BeanScope say:" + this.hashCode());
	}
}
```

```xml
<beans>
	<bean id="beanScope" class="com.bean.BeanScope" scope="prototype"> </bean>
</beans>
```

单元测试

```java
@Test
public void testSay() {
	BeanScope beanScope = super.getBean("beanScope");
	beanScope.say();
	
	BeanScope beanScope2 = super.getBean("beanScope");
	beanScope2.say();
}
```

输出 

```
BeanScope say: 291357518
BeanScope say: 874241356
```


## Bean的生命周期

* 生命周期  
-- 定义  
-- 初始化  
-- 使用  
-- 销毁  


#### 实例一

```java
package com.lifecycle

public class BeanLifeCycle {

	public void start() {
		System.out.println(" Bean start .");
	}
	
	public void stop() {
		System.out.println(" Bean stop .");
	}
	
}
```

```xml
<bean id="beanLifeCycle" class="com.lifecycle.BeanLifeCycle" init-method="start" destroy-method="stop"> </bean>

```

单元测试

```java
@Test
public void test1() {
	BeanLifeCycle bean = super.getBean("beanLifeCycle");
}
```

输出 

```
信息：Loading ...
Bean start .
信息：Closing ...
Bean stop .
```

#### 实例二

```java
package com.lifecycle

public class BeanLifeCycle implements InitializingBean,DisposableBean {

	@Overrider
	public void destroy() throws Exception {
		System.out.println(" Bean destroy .");
	}
	
	@Overrider
	public void afterPropertiesSet() throws Exception {
		System.out.println(" Bean afterPropertiesSet .");
	}
	
}
```

```xml
<bean id="beanLifeCycle" class="com.lifecycle.BeanLifeCycle"> </bean>

```

单元测试

```java
@Test
public void test1() {
	BeanLifeCycle bean = super.getBean("beanLifeCycle");
}
```

输出 

```
信息：Loading ...
Bean afterPropertiesSet .
信息：Closing ...
Bean destroy .
```

#### 实例三

```java
package com.lifecycle

public class BeanLifeCycle {

	public void defaultInit() {
		System.out.println(" Bean defaultInit .");
	}
	
	public void defaultDestroy() {
		System.out.println(" Bean defaultDestroy .");
	}
}
```

```xml
<beans xmlns="···" default-init-method="defaultInit" default-destroy-method="defaultDestroy">
	<bean id="beanLifeCycle" class="com.lifecycle.BeanLifeCycle"> </bean>
</beans>
```

单元测试

```java
@Test
public void test1() {
	BeanLifeCycle bean = super.getBean("beanLifeCycle");
}
```

输出 

```
信息：Loading ...
Bean defaultInit .
信息：Closing ...
Bean defaultDestroy .
```

### 实例四

* **当同时使用了三种方式：实例二先于实例一，实例三不输出(default方式被覆盖)**

输出 

```
信息：Loading ...
Bean afterPropertiesSet .
Bean start .

信息：Closing ...
Bean destroy .
Bean stop .
```

* **当xml中配置defaultInit和Destroy方法，Bean中可以不实现两个默认方法**


## Bean装配之Aware接口

TODO...