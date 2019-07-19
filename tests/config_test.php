<?php
require __DIR__ . '/../vendor/autoload.php';

use luoluolzb\library\Config;

$config = new Config(CONFIG_PATH . 'http_server.php');

print_r($config->get('default_file'));

$config->set('root_path', __DIR__);

print_r($config->get('error_page.404'));
