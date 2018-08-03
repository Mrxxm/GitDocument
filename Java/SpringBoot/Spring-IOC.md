## IOC

* 接口及面向接口编程

* 什么是IOC

* Spring的Bean配置

* Bean初始化

* Spring的常用注入方式

### 接口及面向接口编程

* java8中，接口可以拥有方法体

### 什么是IOC

* IOC: 控制反转(获得依赖对象的过程被反转了)

* DI: 依赖注入(目的：创建对象并且组装对象的关系)

### Spring的Bean配置

IOC中所有的对象都称为bean。

xml配置方式实现(还有另一种注解方式)

```xml
# 编码和版本的说明
<?xml version="1.0" encoding="UTF-8"?>
# 命名空间等说明
<beans xmlns="···">
	# 我们的配置 id=bean的唯一标示 class=对应的类
	<bean id="oneInterface" class="com.ioc.interfaces.OneInterfaceImpl"> </bean>
</brans>
```

实现调用

```java
# 传入xml的配置
public TestOneInterface() {
	super("classpath*:spring-ioc.xml");
}

# getBean()获取相应对象
@Test
public void testHello() {
	OneInterface oneInterface = super.getBean("oneInterface");
	System.out.println(oneInterface.hello("我输入的参数"));
}
```

### bean容器初始化

* 基础：两个包
-- org.springframework.beans  
-- org.springframework.context  
-- BeanFactory提供配置结构和基本功能，加载并初始化Bean  
-- ApplicationContext保存了Bean对象并在Spring中被广泛使用  

* 方式，ApplicationContext  
-- 本地文件  
-- Classpath  
-- Web应用中依赖servlet或Listener  

## Spring注入方式

Spring注入: 指在启动Spring容器加载bean配置的时候，完成对变量赋值的行为。

常用注入方式:

* 设值注入
* 构造注入

### 设值注入(Spring容器自动调用set方法)

```xml
<?xml version="1.0" encoding="UTF-8"?>
<beans xmlns="···">

	<bean id="injectionService" class="com.ioc.injection.service. injectionServiceImpl"> 
		# property属性 在injectionServiceImpl类中包含属性 name=名称为injectionDAO ref=引用id为injectionDAO的实例
		<property name="injectionDAO" ref="injectionDAO"/>
	</bean>
	
	<bean id="injectionDAO" class="com.ioc.injection.dao. injectionDAOImpl"> </bean>
</brans>
```

### 构造注入

构造方法创建实例时赋值injectionServiceImpl的injectionDAO属性injectionDAOImpl实例

```xml
<?xml version="1.0" encoding="UTF-8"?>
<beans xmlns="···">

	<bean id="injectionService" class="com.ioc.injection.service. injectionServiceImpl"> 
		# property属性 在injectionServiceImpl类中包含属性 name=名称为injectionDAO ref=引用id为injectionDAO的实例
		<constructor-arg name="injectionDAO" ref="injectionDAO"/>
	</bean>
	
	<bean id="injectionDAO" class="com.ioc.injection.dao. injectionDAOImpl"> </bean>
</brans>
```

#### 实例(设值注入)：


```xml
<?xml version="1.0" encoding="UTF-8"?>
<beans xmlns="···">

	<bean id="injectionService" class="com.ioc.injection.service. injectionServiceImpl"> 
		# name = injectionServiceImpl中当前成员变量的名称
		# ref = injectionDAOImpl的id
		<property name="injectionDAO" ref="injectionDAO"/>
	</bean>
	
	<bean id="injectionDAO" class="com.ioc.injection.dao. injectionDAOImpl"> </bean>
</brans>
```


**InjectionService**

```java
package com.ioc.injection.service;

public interface InjectionService {

	public void save(String arg);
	
}
```

**InjectionServiceImpl**

```java
package com.ioc.injection.service;

import com.ioc.injection.dao.InjectionDAO;

public class InjectionServiceImpl implements InjectionService {

	private InjectionDAO injectionDAO;
	
	// 设值注入
	public void setInjectionDao(InjectionDAO injectionDAO) {
		this.injectionDAO = injectionDAO;
	}
	
	public void save(String arg) {
		// 模拟业务操作
		System.out.println("Service接受数据: " + arg);
		arg = arg + ":" + this.hashCode();
		injectionDAO.save(arg);
	}
	
}
```

**InjectionDAO**

```java
package com.ioc.injection.dao;

public interface InjectionDAO {

	public void save(String arg);
	
}
```

**InjectionDAOImpl**

```java
package com.ioc.injection.dao;

public class InjectionDAOImpl implements InjectionDAO {
	
	public void save(String arg) {
		// 模拟数据库保存操作
		System.out.println("保存数据: " + arg);
	}
	
}
```

#### 实例(构造注入)：

```xml
<?xml version="1.0" encoding="UTF-8"?>
<beans xmlns="···">

	<bean id="injectionService" class="com.ioc.injection.service. injectionServiceImpl"> 
		# name = injectionServiceImpl中当前成员变量的名称
		# ref = injectionDAOImpl的id
		<constructor-arg name="injectionDAO" ref="injectionDAO"/>
	</bean>
	
	<bean id="injectionDAO" class="com.ioc.injection.dao. injectionDAOImpl"> </bean>
</brans>
```

**InjectionServiceImpl**

```java
package com.ioc.injection.service;

import com.ioc.injection.dao.InjectionDAO;

public class InjectionServiceImpl implements InjectionService {

	private InjectionDAO injectionDAO;
	
	// 构造注入
	public InjectionServiceImpl(InjectionDAO injectionDAO) {
		this.injectionDAO = injectionDAO;
	}
	
	public void save(String arg) {
		// 模拟业务操作
		System.out.println("Service接受数据: " + arg);
		arg = arg + ":" + this.hashCode();
		injectionDAO.save(arg);
	}
	
}
```