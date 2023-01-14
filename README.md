# koriym/mini-cache

* No TTL
* Only string
* Fast

This is the simplest cache available, as no TTL can be specified and only strings can be stored.

It is suitable for storing strings that, once deployed, are unchanged and computationally expensive, such as parsing phpdocs or retrieving unchanged network files.

## Installation

    composer install koriym/mini-cache

##  Usage

```php
$cache = new MiniCache(__DIR__ . '/tmp');
$expensiveComputation = fn() => '1';
$cachedResult = $cache->get('foo', $expensiveComputation);

assert($cachedResult === '1');
```
