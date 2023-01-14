# koriym/mini-cache

* No Expiration
* String Only
* Fast

This is the simplest cache available, as no TTL can be specified and only strings can be stored.

It is suitable for storing strings that, once deployed, are unchanged and computationally expensive, such as parsing phpdocs or retrieving unchanged network files.

Want to store non-string data? You can perform the serialization. Be sure to specify the `allowed_classes` option when saving objects for safe unseiralize.

## Installation

    composer install koriym/mini-cache

##  Usage

```php
$cache = new MiniCache(__DIR__ . '/tmp');
$expensiveComputation = fn() => '1';
$cachedResult = $cache->get('foo', $expensiveComputation);

assert($cachedResult === '1');
```

