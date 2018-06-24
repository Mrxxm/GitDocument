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