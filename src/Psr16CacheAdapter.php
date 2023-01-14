<?php

declare(strict_types=1);

namespace Koriym\MiniCache;

use DateInterval;
use Koriym\MiniCache\Exception\UnsuportedException;
use Psr\SimpleCache\CacheInterface;

use function serialize;
use function unserialize;

final class Psr16CacheAdapter implements CacheInterface
{
    public function __construct(private MiniCache $cache)
    {
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $stored = $this->cache->get($key, static fn () => '');
        if ($stored) {
            return unserialize($stored);
        }

        $this->cache->delete($key);

        return $default;
    }

    public function set(string $key, mixed $value, DateInterval|int|null $ttl = null): bool
    {
        $this->cache->get($key, static fn () => serialize($value));

        return true;
    }

    public function delete(string $key): bool
    {
        return $this->cache->delete($key);
    }

    public function clear(): bool
    {
        return $this->cache->flush();
    }

    /** @codeCoverageIgnore */
    public function getMultiple(iterable $keys, mixed $default = null): iterable // phpcs:ignore
    {
        throw new UnsuportedException(__FUNCTION__);
    }

    /** @codeCoverageIgnore */
    // @phpstan-ignore-next-line
    public function setMultiple(iterable $values, DateInterval|int|null $ttl = null): bool // phpcs:ignore
    {
        throw new UnsuportedException(__FUNCTION__);
    }

    /** @codeCoverageIgnore */
    public function deleteMultiple(iterable $keys): bool // phpcs:ignore
    {
        throw new UnsuportedException(__FUNCTION__);
    }

    public function has(string $key): bool
    {
        return (bool) $this->get($key);
    }
}
