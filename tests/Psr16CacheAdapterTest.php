<?php

declare(strict_types=1);

namespace Koriym\MiniCache;

use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;

class Psr16CacheAdapterTest extends TestCase
{
    private CacheInterface $cache;

    public function setUp(): void
    {
        parent::setUp();

        $this->cache = new Psr16CacheAdapter(new MiniCache(__DIR__ . '/tmp'));
        $this->cache->clear();
    }

    public function testGetMiss(): void
    {
        $stored = $this->cache->get('1', 'default');
        $this->assertSame('default', $stored);
    }

    public function testGetHit(): void
    {
        $this->cache->set('1', 'val1');
        $stored = $this->cache->get('1');
        $this->assertSame('val1', $stored);
    }

    public function testHas(): void
    {
        $this->cache->set('2', '2val');
        $this->assertTrue($this->cache->has('2'));
        $this->assertFalse($this->cache->has('3'));
    }

    public function testDelete(): void
    {
        $this->cache->set('3', '3val');
        $this->cache->delete('3');
        $this->assertFalse($this->cache->has('3'));
    }
}
