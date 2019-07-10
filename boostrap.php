<?php
/**
 * 启动文件
 * @author luoluolzb <luoluolzb@163.com>
 */

/**
 * 定义系统常量
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * 定义框架目录常量
 */
define('APP_PATH', __DIR__ . DS);
define('CONFIG_PATH', APP_PATH . 'config' . DS);
define('VENDOR_PATH', APP_PATH . 'vendor' . DS);
define('LIBRARY_PATH', APP_PATH . 'library' . DS);
define('HTML_PATH', APP_PATH . 'html' . DS);

/**
 * 加载psr-4自动加载类
 */
require LIBRARY_PATH . 'AutoLoader.php';

/**
 * 注册psr-4自动加载规则
 */
luoluolzb\library\AutoLoader::import([
	'luoluolzb' => APP_PATH,
	'luoluolzb\http' => APP_PATH,
]);
