## 安装

1. 在 `start.php` 中强制优先加载本地的 `./support/helpers.php`

```php
#!/usr/bin/env php
<?php

// 避免加载了 laravel/illuminate/foundation/helper.php 导致无法控制顺序的函数重定义报错
require_once __DIR__ . '/support/helpers.php'; // <- here.

require_once __DIR__ . '/vendor/autoload.php';

support\App::run();
```

2. 移除 `composer.json` 中 `autoload.files` 的 `./support/helpers.php` 文件加载

```js
{
    ...
    "autoload": {
        "psr-4": {
            ...
            "": "./",
            "App\\": "./app"
        },
        "files": [] // <- here.
    },
    ...
}
```

3. 安装插件

```
composer require mouyong/webman-laravel:dev-master
```


## 配置项目

**在 config/app.php 中增加如下内容**

```php
return [
    ...

    'name' => 'webman',
    'env' => 'development',
    'providers' => array_filter(array_map(function ($item) {
        if (class_exists($item)) {
            return $item;
        }
    }, [
        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /**
         * 你安装的 laravel 扩展包
         */
        // \Fresns\PluginManager\Providers\PluginServiceProvider::class,

        
        /**
         * 项目的扩展包
         */
        \App\Providers\AppServiceProvider::class,
    ])),

    'aliases' => \Illuminate\Support\Facades\Facade::defaultAliases()->merge([
        // 'ExampleClass' => App\Example\ExampleClass::class,
    ])->toArray(),
];
```


## 更新 composer.json

`composer.json`

```js
    "scripts": {
        // 初始化 laravel-zero/illuminate 与相关配置
        "post-autoload-dump": [
            "MouYong\\WebmanLaravelZero\\ComposerScripts::postAutoloadDump"
        ],
        ...
    }
```


## 增加 Http 启动引导

`config/bootstrap.php`

```php
return [
    \App\LaravelBootstrap::class,
    ...
];
```


## 使用

```shell
php artisan

composer require illuminate/auth # 参考 `laravel` 官方配置 `config` 目录，增加相关的配置项：https://github.com/laravel/laravel
```


## 效果截图

![image](https://user-images.githubusercontent.com/10336437/196345176-9865a0c0-b3cf-4c49-b17a-058480a93a63.png)

![image](https://user-images.githubusercontent.com/10336437/196345268-b0953196-a2b0-49e6-ac2a-ca2eeaeafc0f.png)
