## Vue-axios实现原理

安装：

* `npm install axios`

特点：

* 从浏览器中创建`XMLHttpRequests`
* 从`node.js`创建`http`请求
* 支持`Promise API`
* 拦截请求和响应
* 转换请求数据和响应数据
* 取消请求
* 自动转换`JSON`数据
* 客户端支持预防`XSRF`

全局挂载方式：

* `Vue.use(new VueRouter());`
* `Vue.prototype.$axios = axios;`

请求实例：

```
<script type="text/javascript">

// 挂载axios
Vue.prototype.$axios = axios;

var App = {
    template:'<div><button @click="sendAjax">发请求</button></div>',
    methods:{
        sendAjax() {
            this.$axios.get('http://127.0.0.1:8888')
            .then(res => {
                console.log(res)
            })
            .catch(err => {
                console.log(err)
            })
        }
    }
}
</script>
```

并发请求：

* `baseURL`拼接路由
* `all()`方法，发送多个请求

```
methods:{
    sendAjax() {
        this.$axios.defaults.baseURL = 'http://127.0.0.1:8888/';
        
        var r1 = this.$axios.get('');
        var r2 = this.$axios.post('add', 'a=1');
        
        this.$axios.all([r1, r2])
        .then(this.$axios.spread((res1, res2) => {
            // 请求全部成功
            this.res1 = res1.data;
            this.res2 = res2.data;
        }))
        .catch(err=>{
            // 有一个请求失败就都失败
            console.log(res);
        })
    }
}
```

发送请求数据处理和响应请求数据处理：

* `transformResponse`在传递`then/catch`前，允许修改响应数据
* `transformRequest`使用方法相似，但只能用在 'PUT', 'POST' 和 'PATCH' 这几个请求方法

```
methods:{
    sendAjax() {
        this.$axios.defaults.baseURL = 'http://127.0.0.1:8888/';
        
        this.$axios.get('', {
            params:{id:1},
            transformResponse:[function(data){
            
              // 对data进行任意处理转换
              data = JSON.parse(data);
              return data;
            }]
        })
        .then(res=>{
        
        })
        .catch(res=>{
        
        })
}
```

#### 请求拦截器

```
// 添加请求拦截器
axios.interceptors.request.use(function (config) {
    // 在发送请求之前做些什么
    return config;
  }, function (error) {
    // 对请求错误做些什么
    return Promise.reject(error);
  });

// 添加响应拦截器
axios.interceptors.response.use(function (response) {
    // 对响应数据做点什么
    return response;
  }, function (error) {
    // 对响应错误做点什么
    return Promise.reject(error);
  });
```

