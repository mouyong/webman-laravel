<?php

namespace MouYong\WebmanLaravel;

class ComposerScripts
{
    public static function manualLoadFile($filepath, $manualLoadFile, $action = 'install')
    {
        if (!file_exists($filepath)) {
            return;
        }

        if ($action == 'install') {
            $manualLoadFile = './vendor/mouyong/webman-laravel/' . $manualLoadFile;
        }

        $content = file_get_contents($filepath);
        $json = json_decode($content, true) ?? [];

        $loaded = false;
        foreach ($json['autoload']['files'] ?? [] as $index => $loadFile) {
            if (str_contains($loadFile, $manualLoadFile)) {
                if ($action  == 'remove') {
                    unset($json['autoload']['files'][$index]);
                } else {
                    $loaded = true;
                }
            }
        }

        $manualDumpScripts = [
            "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
            "MouYong\\WebmanLaravel\\ComposerScripts::postAutoloadDump",
        ];

        $manualPsr4Namespace = [
            "Database\\Factories\\" => "database/factories/",
            "Database\\Seeders\\" => "database/seeders/",
        ];

        foreach ($json['scripts']['post-autoload-dump'] ?? [] as $index => $script) {
            if ($action  == 'remove') {
                if (in_array($script, $manualDumpScripts)) {
                    unset($json['scripts']['post-autoload-dump'][$index]);
                }
            }
        }

        if ($action == 'install') {
            if (!$loaded) {
                array_push($json['autoload']['files'], $manualLoadFile);
                $needManualPsr4NamespaceScripts = array_diff($manualPsr4Namespace, $json['autoload']['psr-4']);

                $json['autoload']['psr-4'] = array_merge($needManualPsr4NamespaceScripts, $json['autoload']['psr-4']);

                $needManualDumpScripts = array_diff($manualDumpScripts, $json['scripts']['post-autoload-dump']);
                $json['scripts']['post-autoload-dump'] = array_merge($needManualDumpScripts, $json['scripts']['post-autoload-dump']);
            }
        } else if ($action == 'remove') {
            unset($json['autoload']['psr-4']["Database\\Factories\\"]);
            unset($json['autoload']['psr-4']["Database\\Seeders\\"]);
        }

        try {
            static::write($filepath, $json);
        } catch (\Throwable $e) {
            var_export($e);
        }
    }

    public static function postAutoloadDump($event)
    {
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');

        $rootDir = dirname($vendorDir);
        $manualLoadFile = 'src/helpers.php';
        $rootComposerFile = $rootDir . "/composer.json";
        $laravelZeroComposerFile = $vendorDir . "/mouyong/laravel-foundation/composer.json";

        static::manualLoadFile($laravelZeroComposerFile, $manualLoadFile, 'remove');
        static::manualLoadFile($rootComposerFile, $manualLoadFile, 'install');
    }
    public static function postUninstall($event)
    {
        return;
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');

        $rootDir = dirname($vendorDir);
        $manualLoadFile = 'src/helpers.php';
        $rootComposerFile = $rootDir . "/composer.json";
        static::manualLoadFile($rootComposerFile, $manualLoadFile, 'remove');
    }

    public static function write(string $file, array $json, string $mode = 'r+')
    {
        $fp = fopen($file, $mode);
        flock($fp, LOCK_EX);
        $content = json_encode($json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        fwrite($fp, $content);
        fclose($fp);
    }
}
