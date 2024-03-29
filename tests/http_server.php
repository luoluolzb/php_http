<?php
require __DIR__ . '/../vendor/autoload.php';

use luoluolzb\library\Config;
use luoluolzb\http\Server as HttpServer;
use luoluolzb\http\CookieItem;

const HTML_PATH = __DIR__ . '/../html';

$config = new Config([
    // 监听地址
    'address' => 'localhost',
    // 监听端口（http默认80）
    'port' => 8080,
    'error_page' => [
        '400' => HTML_PATH . '/400.html',
        '404' => HTML_PATH . '/404.html',
        '500' => HTML_PATH . '/500.html',
    ],
    // web目录（自定义配置）
    'web_path' => __DIR__ . '/../html',
]);
$server = new HttpServer($config);

// 监听服务器启动事件
$server->on(
    'start',
    function ($server) {
        $host = $server->address. ':' . $server->port;
        echo "luoluolzb's HttpServer is running at: http://{$host}\n";
    }
);

// 监听服务器关闭事件
$server->on(
    'close',
    function ($server) {
        echo "luoluolzb's HttpServer was closed.\n";
    }
);

// 监听客户端请求事件
$server->on(
    'request',
    function ($request, $response) use ($server) {
        // 输出请求信息
        echo $server->remoteAddress, ':';
        echo $server->remotePort, ' ';
        echo $request->method, ' ';
        echo $request->protocol, ' ';
        echo $request->path, "\n";
    }
);

// 监听'/'请求事件
$server->on(
    '/',
    function ($request, $response) {
        $response->header->set('Content-Type', 'text/html');
        $content = file_get_contents(HTML_PATH . '/index.html');
        $response->body->setContent($content);
    }
);

// 监听'/hello'请求事件
$server->on(
    '/hello',
    function ($request, $response) {
        $query = $request->query;  // 获取url参数
        $response->body->end(print_r($query, true));
    }
);

// 监听'/set-cookie'请求事件
$server->on(
    '/set-cookie',
    function ($request, $response) {
        // 设置cookie
        $response->cookie->set(new CookieItem('name', 'zhangsan'))
            ->set(new CookieItem('age', '22'));
        // 获取所有响应cookie
        $cookieItems = $response->cookie->all();
        $response->body->end(print_r($cookieItems, true));
    }
);

// 监听'/get-cookie'请求事件
$server->on(
    '/get-cookie',
    function ($request, $response) {
        // 获取所有请求cookie
        $cookieItems = $request->cookie->all();
        $response->body->end(print_r($cookieItems, true));
    }
);

$server->start();  // 启动服务器
