<?php

declare(strict_types=1);

namespace Koriym\MiniCache;

use PHPUnit\Framework\TestCase;

class MiniCacheTest extends TestCase
{
    protected MiniCache $cache;

    protected function setUp(): void
    {
        $this->cache = new MiniCache(__DIR__ . '/tmp');
    }

    public function testIsInstanceOfMiniCache(): void
    {
        $actual = $this->cache;
        $this->assertInstanceOf(MiniCache::class, $actual);
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
        $this->assertTrue($this->cache->delete('1'));
    }

    public function testFlush(): void
    {
        $this->cache->get('1', static fn () => '2');
        $this->assertTrue($this->cache->flush());
    }
}
