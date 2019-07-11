<?php
namespace luoluolzb\library;

/**
 * psr-4自动加载器类
 * @author luoluolzb <luoluolzb@163.com>
 */
class AutoLoader
{
    /**
     * 批量导入自动加载规则
     * @param  array[namespace => base_dir(s)] $rules 规则列表
     */
    public static function import($rules): void
    {
        foreach ($rules as $namespace => $dirs) {
            self::register($namespace, $dirs);
        }
    }

    /**
     * 注册psr-4自动加载
     * @param  string               $namespace 基础命名空间
     * @param  array(string)|string $dirs      对应的文件基目录或基目录列表
     */
    public static function register($namespace, $dirs): void
    {
        if (is_array($dirs)) {
            foreach ($dirs as $dir) {
                self::registerOne($namespace, $dir);
            }
        } else {
            self::registerOne($namespace, $dirs);
        }
    }

    /**
     * 注册psr-4自动加载
     * @param  string $namespace 基础命名空间
     * @param  string $baseDir   对应的文件基目录
     */
    protected static function registerOne($namespace, $baseDir): void
    {
        spl_autoload_register(function($className) use ($namespace, $baseDir) {
            if ($namespace == '') {
                $file = str_replace("\\", DS, $baseDir . $className) . '.php';
                if (file_exists($file)) {
                    require $file;
                }
            } elseif (0 === strpos($className, $namespace)) {
                $file = str_replace("\\", DS, realpath($baseDir) . str_replace($namespace, '', $className)) . '.php';
                if (file_exists($file)) {
                    require $file;
                }
            }
        });
    }
}
