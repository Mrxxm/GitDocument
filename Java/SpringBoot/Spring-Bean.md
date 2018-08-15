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

	@Override
	public void destroy() throws Exception {
		System.out.println(" Bean destroy .");
	}
	
	@Override
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

* Spring中提供一些以Aware结尾的接口，实现Aware接口的bean在被初始化之后，可以获取相应资源
* 通过Aware接口，可以对Spring相应资源进行操作(一定要慎重)
* 为对Spring进行简单的扩展提供了方便的入口

#### 实例一

	package com.aware;
	
	import org.···.ApplicationContextAware;
	
	public class MoocApplicationContext implements ApplicationContextAware {
		
		@Override
		public void setApplicationcontext(ApplicationContext applicationContext) throws BeansException {
		System.out.println("MoocApplicationContext: " + applicationContext.getBean("moocApplicationContext").hashCode());
		
		}
	}
	

配置文件

	<bean id="moocApplicationContext" class="com.aware.MoocApplicationContext" > </bean>


单元测试
	
	@Test
	public void testMoocApplicationContext() {
		System.out.println("testApplicationContext: " + super.getBean("moocApplicationContext").hashCode());
	}
	
输出

	MoocApplicationContext: 123454567554
	testApplicationContext: 123454567554
	

#### 实例二

	package com.aware;
	
	import org.···. BeanNameAware;
	
	public class MoocBeanName implements BeanNameAware {
		
		@Override
		public void setBeanName(String name) {
		System.out.println("MoocBeanName: " + name);
		
		}
	}
	
配置文件

	<bean id="moocBeanName" class="com.aware.MoocBeanName" > </bean>

单元测试
	
	@Test
	public void testMoocBeanName() {
		System.out.println("testMoocBeanName: " + super.getBean("moocBeanName"));
	}
		
输出

	MoocBeanName: moocBeanName
	testMoocBeanName: com.aware.MoocBeanName@2342424


## Bean的自动装配

* NO(不做任何操作,默认选项)
* byname(根据属性名自动装配,检查容器并根据名字查找与属性完全一致的bean,并将其属性自动装配)
* byType(容器中存在一个与指定属性类型相同的bean,存在多个相同类型，则抛出异常,如果没有找到则不发生任何事)
* Constructor(与byType方式类似,不同之处在于它应用于构造器参数,如果IOC容器中没有找到与构造器参数一致的bean,那么抛出异常)

#### 实例一
	
配置文件

	<beans xmlns="..." default-autowire="byname">

		<bean id="autoWritingService" class="com.autowriting.AutoWritingService" > </bean>
	
		<bean id="autoWritingDAO" class="com.autowriting.AutoWritingDAO" > </bean>
	
	</beans>
单元测试
	
	public TestAutoWriting() {
		super("classpath:spring-autowriting.xml");
	}
	
	@Test
	public void testSay() {
		AutoWritingService service = super.getBean("autoWritingService");
		
		service.say("this is test");
	}

DAO

	package com.autowriting;
	
	public class AutoWritingDAO{
		public void say(String word) {
			System.out.println("AutoWiringDAO" + word);
		}
	}

Service

	package com.autowriting;
	
	public class AutoWritingService{
	
		private AutoWritingDAO autoWirtingDAO;
	
		public void setAutoWritingDAO(AutoWritingDAO autoWirtingDAO) {
			this.autoWirtingDAO = autoWirtingDAO;
		}
		
		public void say(String word) {
			this.autoWritingDAO.say(word);
		}
	}


#### 实例二(byType和bean的id没有直接的关系)
	
配置文件

	<beans xmlns="..." default-autowire="byType">

		<bean id="autoWritingService" class="com.autowriting.AutoWritingService" > </bean>
	
		<bean id="autoWritingDAO111" class="com.autowriting.AutoWritingDAO" > </bean>
	
	</beans>
单元测试
	
	public TestAutoWriting() {
		super("classpath:spring-autowriting.xml");
	}
	
	@Test
	public void testSay() {
		AutoWritingService service = super.getBean("autoWritingService");
		
		service.say("this is test");
	}

DAO

	package com.autowriting;
	
	public class AutoWritingDAO{
		public void say(String word) {
			System.out.println("AutoWiringDAO" + word);
		}
	}

Service

	package com.autowriting;
	
	public class AutoWritingService{
	
		private AutoWritingDAO autoWirtingDAO;
	
		public void setAutoWritingDAO(AutoWritingDAO autoWirtingDAO) {
			this.autoWirtingDAO = autoWirtingDAO;
		}
		
		public void say(String word) {
			this.autoWritingDAO.say(word);
		}
	}

#### 实例三(构造器与类型相关与id无关)
	
配置文件

	<beans xmlns="..." default-autowire="constructor">

		<bean id="autoWritingService" class="com.autowriting.AutoWritingService" > </bean>
	
		<bean id="autoWritingDAO" class="com.autowriting.AutoWritingDAO" > </bean>
	
	</beans>
单元测试
	
	public TestAutoWriting() {
		super("classpath:spring-autowriting.xml");
	}
	
	@Test
	public void testSay() {
		AutoWritingService service = super.getBean("autoWritingService");
		
		service.say("this is test");
	}

DAO

	package com.autowriting;
	
	public class AutoWritingDAO{
		public void say(String word) {
			System.out.println("AutoWiringDAO" + word);
		}
	}

Service

	package com.autowriting;
	
	public class AutoWritingService{
	
		private AutoWritingDAO autoWirtingDAO;
		
		// 构造方法
		public AutoWritingService(AutoWritingDAO autoWirtingDAO) {
			this.autoWirtingDAO = autoWirtingDAO;
		}
		
		public void say(String word) {
			this.autoWritingDAO.say(word);
		}
	}


## Resources

* 针对资源文件的统一接口

* Resources

  UrlResource : URL对应的资源，根据一个url即可构建  
  ClassPathResource : 获取类路径下的资源文件  
  FileSystemResource : 获取文件系统的资源文件
  ServletContextResource : ServletContext封装的资源，用于访问ServletContext环境下的资源
  InputStreamResource : 针对于输入流封装的资源
  ByteArrayResource : 针对于字节数组封装的资源
  
* ResourceLoader(对Resource进行加载的一个类)


   

TODO...