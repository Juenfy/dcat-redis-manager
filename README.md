
## <center>This is a redis manager for dcat-admin</center>

移植于laravel-admin扩展<a href="https://github.com/laravel-admin-extensions/redis-manager" target="_blank">redis-manager</a>

感谢作者开发的优秀扩展，侵权删！！！

安装
```shell
composer require juenfy/dcat-redis-manager
```

把laravel默认的phpredis改成predis，.env配置添加
```env
REDIS_CLIENT=predis
```

关闭laravel默认的redis前缀，注释app\database.php
```shell
//            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
```
enjoy!!!
