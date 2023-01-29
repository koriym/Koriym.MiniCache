<?php

use Koriym\MiniCache\MiniCacheApc;
use Koriym\MiniCache\MiniCache;
use Koriym\MiniCache\MiniCache;

require dirname(__DIR__, 2). '/src/MiniCache.php';
require dirname(__DIR__, 2). '/src/MiniPhpCache.php';
require dirname(__DIR__, 2). '/src/MiniApcCache.php';
echo '<pre>';

$cache = new MiniCache(__DIR__ . '/tmp');
$expensiveComputation = fn() => '1';
$t = microtime(true);
for ($i = 0; $i < 100000; $i++) {
    $cachedResult = $cache->get('foo', $expensiveComputation);
}
echo microtime(true) - $t . '<br>';

$cache = new MiniCache(__DIR__ . '/tmp');
$t = microtime(true);
for ($i = 0; $i < 100000; $i++) {
    $cachedResult = $cache->get('foo', $expensiveComputation);
}
echo microtime(true) - $t. '<br>';;

$cache = new MiniCacheApc(__DIR__ . '/tmp');
$t = microtime(true);
for ($i = 0; $i < 100000; $i++) {
    $cachedResult = $cache->get('foo', $expensiveComputation);
}
echo microtime(true) - $t;
