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

TODO...