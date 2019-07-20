<?php
require __DIR__ . '/../vendor/autoload.php';

use luoluolzb\library\Config;
use luoluolzb\http\Server as HttpServer;

$server = new HttpServer(new Config([
    // 监听地址
    'address' => 'localhost',
    // 监听端口（http默认80）
    'port' => 8080,
]));

// 监听服务器启动事件
$server->on('start', function ($server) {
    $host = $server->address. ':' . $server->port;
    echo "luoluolzb's HttpServer is running at: http://{$host}\n";
});

// 监听客户端请求事件
$server->on('request', function ($request, $response) use ($server) {
    echo $request->method, ' ', $request->path, "\n";
});

// 监听'/'请求事件
$server->on('/', function ($request, $response) {
    $response->header->set('Content-Type', 'text/html');
    $fileContent = file_get_contents(__DIR__ . '/../html/index.html');
    $response->body->setContent($fileContent);
});

$server->start();  // 启动服务器
