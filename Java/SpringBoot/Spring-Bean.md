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

## Bean的生命周期

* 生命周期  
-- 定义  
-- 初始化  
-- 使用  
-- 销毁  

