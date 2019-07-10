<?php
/**
 * http服务器配置文件
 * @author luoluolzb <luoluolzb@163.com>
 */
return [
	///////////////////////////////////////////////
	// 服务器配置
	///////////////////////////////////////////////
	
	// 监听地址
	'address' => 'localhost',
	
	// 监听端口（http默认80）
	'port' => '8080',

	// 错误状态码时显示的页面文件路径
	'error_page' => [
		400 => HTML_PATH . '400.html',
		404 => HTML_PATH . '404.html',
		500 => HTML_PATH . '500.html',
	],

	///////////////////////////////////////////////
	// 以下为自定义配置
	// 可以使用 $server->conf->get(name) 读取
	///////////////////////////////////////////////
	
	// web根目录
	'root_path' => HTML_PATH,
	
	// 默认文件
	'default_file' => [
		'index.html',
		'index.php'
	],
];