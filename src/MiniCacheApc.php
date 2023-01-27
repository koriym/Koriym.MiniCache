<?php

declare(strict_types=1);

namespace Koriym\MiniCache;

use function apcu_clear_cache;
use function apcu_delete;
use function apcu_exists;
use function apcu_fetch;
use function apcu_store;

final class MiniCacheApc
{
    /** @psalm-param callable():scalar $callback */
    public function get(string $key, callable $callback): mixed
    {
        $exists = apcu_exists($key);
        if (! $exists) {
            $value = $callback();
            apcu_store($key, $value);

            return $value;
        }

        // @codeCoverageIgnoreStart
        return apcu_fetch($key);
        // @codeCoverageIgnoreEnd
    }

    public function delete(string $key): bool
    {
        return apcu_delete($key);
    }

    public function flush(): bool
    {
        return apcu_clear_cache();
    }
}
