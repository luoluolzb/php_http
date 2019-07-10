# php_http
Simple Http Server for PHP

## 使用方法
一个简单的 Http Server 例子：
```php
<?php
require __DIR__ . '/../boostrap.php';
use luoluolzb\http\Server as HttpServer;

$server = new HttpServer(CONFIG_PATH . 'http_server.php');

// 监听服务器启动事件
$server->on('start', function($server) {
	$url = 'http://' . $server->address;
	if ($server->port != 80) {
		$url .= ':' . $server->port;
	}
	echo "luoluolzb's HttpServer is running at: {$url}\n";
});

// 监听客户端请求事件
$server->on('request', function($request, $response) use ($server) {
	echo $server->remoteAddress, ":";
	echo $server->remotePort, " ";
	echo $request->method, ' ';
	echo $request->path, "\n";
});

// 监听'/'请求事件
$server->on('/', function($request, $response) {
	$response->header->set('Content-Type', 'text/html');
	$response->body->content(file_get_contents(HTML_PATH . '/index.html'));
});

$server->start();  // 启动服务器

```

## 运行 Http Server 例子
切换到 `demo` 目录，然后在命令行使用`cli`模式执行脚本：
```shell
php HttpServer.php
```
然后浏览器访问：`http://localhost:8080`。

![welcome](https://github.com/luoluolzb/php_http/blob/master/screenshots/welcome.png?raw=true)

配置文件在 `config/http_server.php`。
