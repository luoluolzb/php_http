<?php
/**
 * psr-4自动加载类
 * @author      luoluolzb
 */
namespace luoluolzb\library;

class AutoLoader
{
	/**
	 * 注册自动加载规则
	 * @param  string               $namespace 基础命名空间
	 * @param  array(string)|string $dirs      对应的文件基目录列表
	 */
	public static function autoload($namespace, $dirs)
	{
		if (is_array($dirs)) {
			foreach ($dirs as $dir) {
				self::_autoload($namespace, $dir);
			}
		} else {
			self::_autoload($namespace, $dirs);
		}
	}

	/**
	 * 批量注册自动加载规则
	 * @param  array[namespace => base_dir] $data 规则列表
	 */
	public static function import($data)
	{
		foreach ($data as $namespace => $dirs) {
			self::autoload($namespace, $dirs);
		}
	}

	/**
	 * 注册psr4自动加载
	 * @param  string $namespace 基础命名空间
	 * @param  string $baseDir   对应的文件基目录
	 */
	protected static function _autoload($namespace, $baseDir)
	{
		spl_autoload_register(function($className) use ($namespace, $baseDir){
			if ($namespace == '') {
				$file = str_replace('\\', DS, $baseDir . $className) . '.php';
				if (file_exists($file)) {
					require $file;
				}
			} else if (0 === strpos($className, $namespace)) {
				$file = str_replace('\\', DS, realpath($baseDir) . str_replace($namespace, '', $className)) . '.php';
				if (file_exists($file)) {
					require $file;
				}
			}
		});
	}
}
