<?php
require __DIR__ . '/../vendor/autoload.php';

use luoluolzb\http\Body;

$body = new Body('456');
$body->end("abc\n")->endln('def')->beginln('123');
var_dump($body->length());
echo $body->getContent();
