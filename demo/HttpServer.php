<?php
require __DIR__ . '/../boostrap.php';
use luoluolzb\http\Server as HttpServer;
use luoluolzb\http\CookieItem;

$server = new HttpServer(CONFIG_PATH . 'http_server.php');

// 监听服务器启动事件
$server->on('start', function($server) {
	$url = 'http://' . $server->address;
	if ($server->port != 80) {
		$url .= ':' . $server->port;
	}
	echo "luoluolzb's HttpServer is running at: {$url}\n";
});

// 监听服务器关闭事件
$server->on('close', function($server) {
	echo "luoluolzb's HttpServer was closed.\n";
});

// 监听客户端请求事件
$server->on('request', function($request, $response) use($server) {
	// 客户端地址
	echo $server->remoteAddress, ":";
	// 客户端连接端口
	echo $server->remotePort, " ";
	// 请求方法
	echo $request->method, ' ';
	// 请求url
	echo $request->url, "\n";
});

// 监听 '/' 请求事件
$server->on('/', function($request, $response) {
	$response->header->set('Content-Type', 'text/html');
	$response->body->content(file_get_contents(HTML_PATH . '/index.html'));
});

// 监听 '/hello' 请求事件
$server->on('/hello', function($request, $response) {
	$response->header->set('Content-Type', 'text/plain');
	$response->body->content(print_r($request->query, true));
});

// 监听 '/set-cookie' 请求
$server->on('/set-cookie', function($request, $response) {
	$response->header->set('Content-Type', 'text/plain');
	$cookieItems = $response->cookie
	->set(new CookieItem('name', 'zhangsan'))
	->set(new CookieItem('age', '22'))
	->all();
	$response->body->content(print_r($cookieItems, true));
});

// 监听 '/get-cookie' 请求
$server->on('/get-cookie', function($request, $response) {
	$response->header->set('Content-Type', 'text/plain');
	$cookieItems = $request->cookie->all();
	$response->body->content(print_r($cookieItems, true));
});

$server->start();  // 启动服务器
