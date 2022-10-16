<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;

class ZeroBootstrap
{
    // 引入 laravel 测试 内存占用
    public static function start()
    {
        define('LARAVEL_START', microtime(true));

        require base_path() . '/vendor/autoload.php';

        if (file_exists($maintenance = base_path() . '/runtime/framework/maintenance.php')) {
            require $maintenance;
        }

        /** @var Application */
        $app = require_once base_path() . '/bootstrap/app.php';

        $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

        $kernel->bootstrap();

        // $response = $kernel->handle(
        //     $request = Request::capture()
        // )->send();

        // $kernel->terminate($request, $response);
    }
}
