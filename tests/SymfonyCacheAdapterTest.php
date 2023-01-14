<?php

declare(strict_types=1);

namespace Koriym\MiniCache;

use PHPUnit\Framework\TestCase;

class SymfonyCacheAdapterTest extends TestCase
{
    private SymfonyCacheAdapter $cache;

    public function setUp(): void
    {
        parent::setUp();

        $this->cache = new SymfonyCacheAdapter(new MiniCache(__DIR__ . '/tmp'));
    }

    public function testGetMiss(): void
    {
        $stored = $this->cache->get('1', static fn () => '1');
        $this->assertSame('1', $stored);
    }

    public function testGetHit(): void
    {
        $stored = $this->cache->get('1', static fn () => '1');
        $this->assertSame('1', $stored);
    }

    public function testDelete(): void
    {
        $this->assertTrue($this->cache->delete('1'));
    }
}
