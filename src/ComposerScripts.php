<?php

namespace MouYong\WebmanIlluminate;

class ComposerScripts
{
    public static function manualLoadFile($filepath, $manualLoadFile, $action = 'install')
    {
        if (!file_exists($filepath)) {
            return;
        }

        if ($action == 'install') {
            $manualLoadFile = './vendor/laravel-zero/foundation/'.$manualLoadFile;
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

        $manualDumpScript = "MouYong\\WebmanIlluminate\\ComposerScripts::postAutoloadDump";
        foreach ($json['scripts']['post-autoload-dump'] ?? [] as $index => $script) {
            if (str_contains($script, $manualDumpScript)) {
                if ($action  == 'remove') {
                    unset($json['scripts']['post-autoload-dump'][$index]);
                }
            }
        }

        if ($action == 'install') {
            if (!$loaded) {
                array_push($json['autoload']['files'], $manualLoadFile);

                $json['autoload']['psr-4']["Database\\Factories\\"] = "database/factories/";
                $json['autoload']['psr-4']["Database\\Seeders\\"] = "database/seeders/";

                if (!in_array($manualDumpScript, $json['scripts']['post-autoload-dump'] ?? [])) {
                    array_push($json['scripts']['post-autoload-dump'], $manualDumpScript);
                }

                if (empty($json['bin'])) {
                    $json['bin'] = ['zero'];
                }
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
        $manualLoadFile = 'src/Illuminate/Foundation/helpers.php';
        $rootComposerFile = $rootDir . "/composer.json";
        $laravelZeroComposerFile = $vendorDir . "/laravel-zero/foundation/composer.json";

        static::manualLoadFile($laravelZeroComposerFile, $manualLoadFile, 'remove');
        static::manualLoadFile($rootComposerFile, $manualLoadFile, 'install');
    }
    public static function postUninstall($event)
    {
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');

        $rootDir = dirname($vendorDir);
        $manualLoadFile = 'src/Illuminate/Foundation/helpers.php';
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
