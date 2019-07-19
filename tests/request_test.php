<?php
require __DIR__ . '/../vendor/autoload.php';

use luoluolzb\http\Request;

$rawContent = <<<EOF
GET /index?name=luoluo&age=22 HTTP/1.1
Host: localhost:8080
Connection: keep-alive
Upgrade-Insecure-Requests: 1
User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3
Accept-Encoding: gzip, deflate, br
Accept-Language: zh-CN,zh;q=0.9,en;q=0.8
Cookie: delPer=0

this is content.
EOF;
$request = new Request();
$request->parseRequestRaw($rawContent);
if ($request->isOk()) {
    print_r($request);
} else {
    echo "Bad Request";
}
