<?php

declare(strict_types=1);

namespace Koriym\MiniCache;

use PHPUnit\Framework\TestCase;

class MiniCacheApcTest extends TestCase
{
    protected MiniCacheApc $cache;

    protected function setUp(): void
    {
        $this->cache = new MiniCacheApc();
    }

    public function testGet(): void
    {
        $actual = $this->cache->get('1', static fn () => '2');
        $this->assertSame('2', $actual);
        $actual = $this->cache->get('1', static fn () => '2');
        $this->assertSame('2', $actual);
    }

    public function testDelete(): void
    {
        $this->cache->get('1', static fn () => '2');
        $this->cache->delete('1');
        $actual = $this->cache->get('1', static fn () => '3');
        $this->assertSame($actual, '3');
    }

    public function testFlush(): void
    {
        $this->cache->get('1', static fn () => '2');
        $this->assertTrue($this->cache->flush());
    }
}
