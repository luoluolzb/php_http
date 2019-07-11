<?php
require __DIR__ . '/../boostrap.php';

use luoluolzb\http\Server as HttpServer;
use luoluolzb\http\CookieItem;

$server = new HttpServer(CONFIG_PATH . 'http_server.php');

// 监听服务器启动事件
$server->on('start', function($server) {
	$host = $server->address. ':' . $server->port;
	echo "luoluolzb's HttpServer is running at: http://{$host}\n";
});

// 监听服务器关闭事件
$server->on('close', function($server) {
	echo "luoluolzb's HttpServer was closed.\n";
});

// 监听客户端请求事件
$server->on('request', function($request, $response) use ($server) {
	// 输出请求信息
	echo $server->remoteAddress, ':';
	echo $server->remotePort, ' ';
	echo $request->method, ' ';
	echo $request->protocol, ' ';
	echo $request->path, "\n";
});

// 监听'/'请求事件
$server->on('/', function($request, $response) {
	$response->header->set('Content-Type', 'text/html');
	$content = file_get_contents(HTML_PATH . '/index.html');
	$response->body->content($content);
});

// 监听'/hello'请求事件
$server->on('/hello', function($request, $response) {
	$query = $request->query;  // 获取url参数
	$response->body->content(print_r($query, true));
});

// 监听'/set-cookie'请求事件
$server->on('/set-cookie', function($request, $response) {
	// 设置cookie
	$response->cookie->set(new CookieItem('name', 'zhangsan'))
	->set(new CookieItem('age', '22'));
	// 获取所有响应cookie
	$cookieItems = $response->cookie->all();
	$response->body->content(print_r($cookieItems, true));
});

// 监听'/get-cookie'请求事件
$server->on('/get-cookie', function($request, $response) {
	// 获取所有请求cookie
	$cookieItems = $request->cookie->all();
	$response->body->content(print_r($cookieItems, true));
});

$server->start();  // 启动服务器
