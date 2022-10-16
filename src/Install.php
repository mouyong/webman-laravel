<?php
namespace MouYong\WebmanIlluminate;

class Install
{
    const WEBMAN_PLUGIN = true;

    /**
     * @var array
     */
    protected static $pathRelation = array (
        'config/plugin/mouyong/webman-illuminate' => 'config/plugin/mouyong/webman-illuminate',
        'stubs/app' => 'app',
        'stubs/bootstrap' => 'bootstrap',
        'stubs/zero' => 'zero',
        'stubs/box.json' => 'box.json',
    );

    protected static $mergeConfigRelation = array (
        'stubs/config/app.php' => 'config/app.php',
        'stubs/config/commands.php' => 'config/commands.php',
    );

    /**
     * Install
     * @return void
     */
    public static function install()
    {
        static::installByRelation();
        static::mergeRelation();
    }

    public static function mergeRelation()
    {
        foreach(static::$mergeConfigRelation as $source => $dest) {
            if ($pos = strrpos($dest, '/')) {
                $parent_dir = base_path().'/'.substr($dest, 0, $pos);
                if (!is_dir($parent_dir)) {
                    mkdir($parent_dir, 0777, true);
                }
            }

            $destPath = base_path()."/$dest";
            if (file_exists($destPath)) {
                $newDestPath = $destPath.".new.php";

                if (!file_exists($newDestPath)) {
                    copy_dir(__DIR__ . "/$source", $newDestPath);
                
                    echo "
    $dest config need manual merge
    
    ";
                }
            } else {
                copy_dir(__DIR__ . "/$source", $destPath);
            }

            echo "Create $dest
";
        }
    }

    /**
     * Uninstall
     * @return void
     */
    public static function uninstall()
    {
        self::uninstallByRelation();
    }

    /**
     * installByRelation
     * @return void
     */
    public static function installByRelation()
    {
        foreach (static::$pathRelation as $source => $dest) {
            if ($pos = strrpos($dest, '/')) {
                $parent_dir = base_path().'/'.substr($dest, 0, $pos);
                if (!is_dir($parent_dir)) {
                    mkdir($parent_dir, 0777, true);
                }
            }
            //symlink(__DIR__ . "/$source", base_path()."/$dest");
            copy_dir(__DIR__ . "/$source", base_path()."/$dest");
            echo "Create $dest
";
        }

        chmod(base_path()."/zero", 0755);
    }

    /**
     * uninstallByRelation
     * @return void
     */
    public static function uninstallByRelation()
    {
        foreach (static::$pathRelation as $source => $dest) {
            $path = base_path()."/$dest";
            if (!is_dir($path) && !is_file($path)) {
                continue;
            }
            echo "Remove $dest
";
            if (is_file($path) || is_link($path)) {
                // unlink($path);
                continue;
            }
            // remove_dir($path);
        }
    }
}