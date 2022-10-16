## 安装

```
 composer require mouyong/webman-laravel-zero:dev-master laravel-zero/foundation:dev-main

php zero app:install database
composer require illuminate/routing
```

## 配置项目

在 config/app.php 中增加如下内容
```
return [
    ...

    # 以下内容可参考 config/app.php.new.php 文件
    'name' => "Zero", // 单引号，首字母大写。app:rename 匹配使用的
    // 'version' => app('git.version'),
    'version' => '1.0.0',
    'env' => 'development',
    'timezone' => 'PRC',
    'providers' => [
        App\Providers\AppServiceProvider::class,
    ],
];
```

## 更新 composer.json

`composer.json`
```js
    "bin": ["zero"], // 这行要原样保存，app:rename 使用
    "scripts": {
        // 初始化 laravel-zero/illuminate 与相关配置
        "post-autoload-dump": [
            "MouYong\\WebmanIlluminate\\ComposerScripts::postAutoloadDump"
        ],
        ...
    }
```
