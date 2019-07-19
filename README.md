# php_http
纯PHP实现的简单的http服务器，仅用于学习http，不建议用于实际开发。

## 安装
使用composer安装：
```
composer require luoluolzb/php_http
```

## 使用
一个简单的 Http Server 例子：
```php
<?php
require __DIR__ . '/boostrap.php';
use luoluolzb\http\Server as HttpServer;

$server = new HttpServer(CONFIG_PATH . 'http_server.php');

// 监听服务器启动事件
$server->on('start', function($server) {
	$host = $server->address. ':' . $server->port;
	echo "luoluolzb's HttpServer is running at: http://{$host}\n";
});

// 监听客户端请求事件
$server->on('request', function($request, $response) use ($server) {
	echo $request->method, ' ', $request->path, "\n";
});

// 监听'/'请求事件
$server->on('/', function($request, $response) {
	$response->header->set('Content-Type', 'text/html');
	$fileContent = file_get_contents(HTML_PATH . 'index.html');
	$response->body->content($fileContent);
});

$server->start();  // 启动服务器

```

## 运行 Http Server 例子
切换到 `demo` 目录，然后在命令行使用`cli`模式执行脚本：
```shell
php HttpServer1.php
```

然后浏览器访问：`http://localhost:8080`。

![welcome](https://github.com/luoluolzb/php_http/blob/master/screenshots/welcome.png?raw=true)

配置文件在 `config/http_server.php`。

## 运行文件浏览server例子
切换到 `demo` 目录，然后在命令行使用`cli`模式执行脚本：
```shell
php HttpServer2.php
```
