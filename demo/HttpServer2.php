<?php
require __DIR__ . '/../boostrap.php';

use luoluolzb\http\Server as HttpServer;
use luoluolzb\library\Mime;

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

    // 处理请求
    $path = $server->conf->get('web_path') . DS . $request->path;
    if (file_exists($path)) {
        if (is_dir($path)) {
            $path .= DS . 'index.html';
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
