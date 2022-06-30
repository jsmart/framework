<?php

use JSmart\Core\Foundation\Http\Kernel;
use JSmart\Core\Foundation\Http\Request;

define('JSMART', microtime(true));

require_once __DIR__ . '/vendor/autoload.php';

$JSmart = new JSmart\Core\Foundation\Application(__DIR__);

$kernel = $JSmart->make(Kernel::class);

$kernel->handle(
    Request::createFromGlobals()
)->send();
