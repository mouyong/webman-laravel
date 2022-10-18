## 安装

```
composer config repositories.webman-laravel-zero-foundation vcs https://github.com/mouyong/webman-laravel-zero-foundation

composer require mouyong/webman-laravel-zero:dev-master laravel-zero/foundation:dev-main
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
