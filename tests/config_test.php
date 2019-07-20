<?php
require __DIR__ . '/../vendor/autoload.php';

use luoluolzb\library\Config;

$config = new Config(
    [
    // 监听地址
    'address' => 'localhost',
    // 监听端口（http默认80）
    'port' => 8080,
    ]
);

print_r($config->get('default_file'));

$config->set('root_path', __DIR__);

print_r($config->get('error_page.404'));
