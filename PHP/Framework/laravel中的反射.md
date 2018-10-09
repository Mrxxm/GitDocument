## laravel中的反射

laravel整个框架设计的"优雅"就是在于container、IOC、依赖注入。我们来看一下容器中一段关于反射的代码:

**`IlluminateContainerContainer`:**

```php
/**
     * Instantiate a concrete instance of the given type.
     *
     * @param  string  $concrete
     * @param  array   $parameters
     * @return mixed
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function build($concrete, array $parameters = [])
    {
        // If the concrete type is actually a Closure, we will just execute it and
        // hand back the results of the functions, which allows functions to be
        // used as resolvers for more fine-tuned resolution of these objects.
        if ($concrete instanceof Closure) {
            return $concrete($this, $parameters);
        }

        $reflector = new ReflectionClass($concrete);

        // If the type is not instantiable, the developer is attempting to resolve
        // an abstract type such as an Interface of Abstract Class and there is
        // no binding registered for the abstractions so we need to bail out.
        if (! $reflector->isInstantiable()) {
            if (! empty($this->buildStack)) {
                $previous = implode(', ', $this->buildStack);

                $message = "Target [$concrete] is not instantiable while building [$previous].";
            } else {
                $message = "Target [$concrete] is not instantiable.";
            }

            throw new BindingResolutionException($message);
        }

        $this->buildStack[] = $concrete;

        $constructor = $reflector->getConstructor();

        // If there are no constructors, that means there are no dependencies then
        // we can just resolve the instances of the objects right away, without
        // resolving any other types or dependencies out of these containers.
        if (is_null($constructor)) {
            array_pop($this->buildStack);

            return new $concrete;
        }

        $dependencies = $constructor->getParameters();

        // Once we have all the constructor's parameters we can create each of the
        // dependency instances and then use the reflection instances to make a
        // new instance of this class, injecting the created dependencies in.
        $parameters = $this->keyParametersByArgument(
            $dependencies, $parameters
        );

        $instances = $this->getDependencies(
            $dependencies, $parameters
        );

        array_pop($this->buildStack);

        return $reflector->newInstanceArgs($instances);
    }
```

就是实现绑定类的方法,build方法。下面我们就来分析一下:

* 参数:`$concreate string` 类似于`Model::class`这种嘛，不难理解。`$parameters array` 参数 更不难理解了吧。
* 判断 `$concreate` 是否是匿名类(闭包),是匿名类就执行这个函数.
* 创建反射类，去映射这个类。
* 判断这个类能否被实例化,也就是看构造函数是否是private。否就抛出出异常。
* 在容器成员变量中数组维护这个类，反射实例调用构造函数，获取返回值。
* 判断返回值是否为空，如果为空就说明不需要参数依赖，那么就直接实例化。否则就获取构造函数的参数依赖，将传入的参数和依赖参数进行对照。
* 最后，在调用`newInstanceArgs`进行实例化，之后返回实例。