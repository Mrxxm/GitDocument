## ssh

**持久层 Hibernate**

```java
/**
* 1.加载配置文件
* 2.创建session factory对象，生成Session对象
* 3.创建session对象
* 4.开启事务
* 5.编写保存代码
* 6.提交事务
* 7.释放资源
*/
public class BookDao{
	public void save(Book book){
		// 1.先加载配置文件,默认加载src目录下的hibernate.cfg.xml文件
		Configuration cfg = new Configuration().configure();
		// 2.创建session factory对象
		SessionFactory factory = config.buildSessionFactory();
		// 3.创建session对象 持久化对象可以做增删改查
		Session session = factory.openSession();
		// 4.开启事务
		Transaction tr = session.beginTransaction();
		// 5.编写保存代码
		session.save(book);
		// 6.提交事务
		tr.commit();
		// 7.释放资源
		session.close();
	}
} 
```

**业务层 Spring**

```java
/**
* 将service、dao都交给Spring管理，并且在service中注入dao
*
*/
public class BookService{
	private BookDao bookDao;
	public void setBookDao(Book bookDao){
		this.bookDao = bookDao;
	}
	
	public void save(Book book){
		bookDao.save(book);
	}
}
```

xml配置

```xml
<bean id="bookDao" class="...BookDao(填写全路径)">
</bean>

<bean id="bookService" class="...BookService(填写全路径)">
	<property name="bookDao" ref="bookDao" />
</bean>
```

**视图层 Struts2**

```java
public class BookAction extends ActionSupport implements ModelDriven<Book>{

	private Book book = new Book();
	
	public Book getModel(){
		return book;
	}
	
	public String save(){
		WebApplicationContent wap = WebApplicationContextUtils.getWebApplicationContext(...);
		BookService bs = wap.getBean("bookService");
		bs.save(book);
	}
}
```

## 环境搭建

**了解的jar包：** 

`struts2-convention-plugin-2.3.15.3.jar` ---struts2的注解开发的jar包  

`struts2-spring-plugin-2.3.15.3.jar` ---struts2用于整合spring的jar包

**Hibernate框架开发的相应的jar：**

`hibernate-distribution-3.6.10.Final\hibernate3.jar`  
`hibernate-distribution-3.6.10.Final\lib\required\*.jar`  
`hibernate-distribution-3.6.10.Final\lib\jpa\*.jar`  
日志记录：    

* slf4j整合log4j的jar包

数据库驱动包：

*  

**spring框架开发的相应的jar：**

IOC:   
`spring-beans-3.2.0.RELEASE.jar`  
`spring-context-3.2.0.RELEASE.jar`  
`spring-core-3.2.0.RELEASE.jar`  
`spring-expression-3.2.0.RELEASE.jar`  
`com.springsource.org.apache.log4j-1.2.15.jar` ---日志记录  
`com.springsource.org.apache.commons-logging-1.1.1.jar` ---进行日志整合  
AOP:  
`spring-aop-3.2.0.RELEASE.jar`  
`spring-aspects-3.2.0.RELEASE.jar`  
`com.springsource.org.aopalliance-1.0.0.jar`  
`com.springsource.org.aspect.weaver-1.6.8.RELEASE.jar`  
事务管理：  

* `spring-tx-3.2.0.RELEASE.jar`  
* `spring-jdbc-3.2.0.RELEASE.jar`  

整合Hibernate的包：

* `spring-orm-3.2.0.RELEASE.jar`  

整合WEB项目

* `spring-web-3.2.0.RELEASE.jar`  

整合Junit单元测试

* `spring-test-3.2.0.RELEASE.jar`

连接池

* `com.springsrource.com.mchange.v2.c3p0-0.9.1.2`  

**Struts2框架配置文件**

* web.xml --struts2核心过滤器 

```xml
  <!-- Struts2框架的核心过滤器的配置 -->
  <filter>
    <filter-name>struts</filter-name>
	<filter-class>org.apache.struts2.dispatcher.ng.filter.StrutsPrepareAndExecuteFilter</filter-class>
  </filter>
	
  <filter-mapping>
	<filter-name>struts</filter-name>
	<url-pattern>/*</url-pattern>
  </filter-mapping>
```
 
* struts.xml --struts2本身配置文件(添加到src目录下)

```xml
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE struts PUBLIC
	"-//Apache Software Foundation//DTD Struts Configuration 2.3//EN"
	"http://struts.apache.org/dtds/struts-2.3.dtd">

<struts>
</struts>

```

**Hibernate框架配置文件**

* hibernate.cfg.xml --Hibernate本身配置文件(在ssh整合中该配置文件可以省略)
* 映射文件

**Spring框架配置文件**

* web.xml --配置核心监听器

```xml
  <!-- Spring的框架的核心监听器 -->
  <listener>
	<listener-class>org.springframework.web.context.ContextLoaderListener</listener-class>
  </listener>

  <context-param>
	<param-name>contextConfigLocation</param-name>
	<param-value>classpath:applicationContext.xml</param-value>
  </context-param>
```

* applicationContext.xml --本身配置文件(添加到src目录下)

**创建包结构**

```
cn.xxm.ssh.action
cn.xxm.ssh.service
cn.xxm.ssh.dao
cn.xxm.ssh.domain
```

**创建实体类**

```java
package cn.xxm.ssh.domain;
/**
 * 商品属性
 */
public class Product {
	private Integer pid;
	private String pname;
	private Double price;
	public Integer getPid() {
		return pid;
	}
	public void setPid(Integer pid) {
		this.pid = pid;
	}
	public String getPname() {
		return pname;
	}
	public void setPname(String pname) {
		this.pname = pname;
	}
	public Double getPrice() {
		return price;
	}
	public void setPrice(Double price) {
		this.price = price;
	}	
}
```

## Struts2整合Spring

**创建页面(保存商品)**