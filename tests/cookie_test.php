<?php
require __DIR__ . '/../vendor/autoload.php';

use luoluolzb\http\Cookie;
use luoluolzb\http\CookieItem;

$raw = 'delPer=0';

$cookie = new Cookie();
$cookie->parseRequestRaw($raw);
// print_r($cookie);

// print_r($cookie->makeResponseRaws());

$cookie->clear()
->set(new CookieItem('name', 'luoluolzb'))
->set(new CookieItem('age', '20'))
->delete('name')
->set(new CookieItem('id', '109e03'));

print_r($cookie->all());
// var_dump($cookie->exists('name'));
// print_r($cookie->get('id'));

// $cookie2 = clone $cookie;
// $cookie2->clear();
// print_r($cookie);
// print_r($cookie2);
