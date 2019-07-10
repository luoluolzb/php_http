<?php
/**
 * http服务器配置文件
 * @author luoluolzb <luoluolzb@163.com>
 */
return [
	// 监听地址
	'address' => 'localhost',
	
	// 监听端口（http默认80）
	'port' => '8080',
	
	// web根目录
	'root_path' => 'D:\Program Files\PHP\PHPTutorial\WWW',
	
	// 默认文件
	'default_file' => [
		'index.html',
		'index.php'
	],

	// 错误状态码时显示的页面文件路径
	'error_page' => [
		400 => HTML_PATH . '400.html',
		404 => HTML_PATH . '404.html',
		500 => HTML_PATH . '500.html',
	],
];
