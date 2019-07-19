# php_http
纯PHP实现的简单的http服务器，仅用于学习http，不建议用于实际开发。

运行效果：

![welcome](https://github.com/luoluolzb/php_http/blob/master/screenshots/welcome.png?raw=true)

![welcome](https://github.com/luoluolzb/php_http/blob/master/screenshots/404.png?raw=true)


## 安装
使用composer安装：
```
composer require luoluolzb/php_http
```


## 使用例子
## 监听'/'请求并处理
```php
<?php
require __DIR__ . '/vendor/autoload.php';

use luoluolzb\library\Config;
use luoluolzb\http\Server as HttpServer;

$server = new HttpServer(new Config([
    // 监听地址
    'address' => 'localhost',
    // 监听端口（http默认80）
    'port' => 8080,
]));

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
    $response->body->end('hello, world!');
});

$server->start();  // 启动服务器

```

请在`php-cli`模式下运行此代码，下同。


## 简单web服务器
```php
<?php
require __DIR__ . '/vendor/autoload.php';

use luoluolzb\library\{Config, Mime};
use luoluolzb\http\Server as HttpServer;

$server = new HttpServer(new Config([
    // 监听地址
    'address' => 'localhost',
    // 监听端口（http默认80）
    'port' => 8081,
    // web目录（自定义配置）
    'web_path' => '/path/to/html',
]));

// 监听服务器启动事件
$server->on('start', function ($server) {
    $host = $server->address. ':' . $server->port;
    echo "luoluolzb's HttpServer is running at: http://{$host}\n";
});

// 监听服务器关闭事件
$server->on('close', function ($server) {
    echo "luoluolzb's HttpServer was closed.\n";
});

// 监听客户端请求事件
$server->on('request', function ($request, $response) use ($server) {
    // 输出请求信息
    echo $request->method, ' ', $request->path, "\n";

    // 处理请求
    // 读取请求的文件内容并返回给客户端
    $path = $server->conf->get('web_path') . $request->path;
    if (file_exists($path)) {
        if (is_dir($path)) {
            $path .= '/index.html';
        }
        if (file_exists($path)) {
            $content = file_get_contents($path);
            $response->header->set('Content-Type', Mime::get($path));
            $response->body->content($content);
        } else {
            $server->badRequest(404);
        }
    } else {
        $server->badRequest(404);
    }

    // 已处理所有请求
    $request->setFinish();
});

$server->start();  // 启动服务器

```
