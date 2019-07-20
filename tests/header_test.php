<?php
require __DIR__ . '/../vendor/autoload.php';

use luoluolzb\http\Header;

$header = new Header();

$raw = <<<EOF
POST /index.html?arg=124 HTTP/1.1
Host: localhost:8080
Connection: keep-alive
Upgrade-Insecure-Requests: 1
User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3
Accept-Encoding: gzip, deflate, br
Accept-Language: zh-CN,zh;q=0.9,en;q=0.8
EOF;
$header->parseRequestRaw($raw);
// print_r($header);
// echo $header->makeResponseRaw(200);

$header->set('Host', 'localhost:8080');
$header->set('Connection', 'close');
$header->set('Content-Length', '0');
$header->set('Set-Cookie', 'aaaaa', true);
$header->set('Set-Cookie', 'bbbb', true);
echo $header->makeResponseRaw(404);

var_dump($header->get('Set-Cookie'));
var_dump($header->get('Connection'));
