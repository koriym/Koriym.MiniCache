<?php

use Koriym\MiniCache\MiniCache;

require dirname(__DIR__, 1). '/vendor/autoload.php';

$cache = new MiniCache(__DIR__ . '/tmp');
$expensiveComputation = fn() => '1';
$cachedResult = $cache->get('foo', $expensiveComputation);

$works = $cachedResult === '1';
echo($works ? 'It works!' : 'It DOES NOT work!') . PHP_EOL;
