<?php

declare(strict_types=1);

namespace Koriym\MiniCache;

use Symfony\Contracts\Cache\CacheInterface;

final class SymfonyCacheAdapter implements CacheInterface
{
    public function __construct(private MiniCache $cache)
    {
    }

    /** @psalm-suppress MixedArgumentTypeCoercion */
    public function get(string $key, callable $callback, ?float $beta = null, ?array &$metadata = null): mixed // @phpstan-ignore-line
    {
        return $this->cache->get($key, $callback);
    }

    public function delete(string $key): bool
    {
        return $this->cache->delete($key);
    }
}
