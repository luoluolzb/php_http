<?php
/**
 * demo 启动文件
 * @author luoluolzb <luoluolzb@163.com>
 */

/**
 * 定义目录符快捷常量
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * 定义目录常量
 */
define('DEMO_PATH', __DIR__ . DS);
define('CONFIG_PATH', DEMO_PATH . 'config' . DS);
define('HTML_PATH', DEMO_PATH . 'html' . DS);

require __DIR__ . '/../vendor/autoload.php';
