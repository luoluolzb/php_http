<?php
require __DIR__ . '/../vendor/autoload.php';

use luoluolzb\http\CookieItem;

$item = new CookieItem('abc', 'abc');
$item->domain = '45';
print_r($item);
var_dump($item->makeResponseRaw());
