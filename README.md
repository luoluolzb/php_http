# php_http
Simple Http Server for PHP

## 运行 HttpServer
切换到 `demo` 目录，然后在命令行使用`cli`模式执行脚本：
```shell
php HttpServer.php
```
然后浏览器访问：`http://localhost:8080`。

配置文件在 `config/http_server.php`。

你可以修改 `demo/HttpServer.php` 代码：
```php
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
$server->on('request', function($request, $response) {
	echo $request->method . ' ' . $request->url . "\n";
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

	$response->cookie
	->set(new CookieItem('name', 'zhangsan'))
	->set(new CookieItem('age', '22'));

	$cookieItems = $request->cookie->all();
	$response->body->content(print_r($cookieItems, true));
});

// 监听 '/get-cookie' 请求
$server->on('/get-cookie', function($request, $response) {
	$response->header->set('Content-Type', 'text/plain');

	$cookieItems = $request->cookie->all();
	$response->body->content(print_r($cookieItems, true));
});

$server->start();  // 启动服务器

```
