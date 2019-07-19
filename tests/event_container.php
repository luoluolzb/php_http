<?php
require __DIR__ . '/../vendor/autoload.php';

use luoluolzb\library\EventContainer;

$container = new EventContainer();

$container->bind('say', function ($name) {
    echo "my name is ${name}.\n";
});

$container->bind('say', function ($name) {
    echo "your name is ${name}.\n";
});

$container->trigger('say', 'luoluo');
