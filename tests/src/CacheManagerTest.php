<?php

declare(strict_types=1);

namespace Xylemical\Cache;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

/**
 * Tests \Xylemical\Cache\CacheManager.
 */
class CacheManagerTest extends TestCase {

  use ProphecyTrait;

  /**
   * Tests sanity.
   */
  public function testSanity(): void {
    $cache = $this->prophesize(CacheInterface::class);
    $cache->getType()->willReturn(CacheInterface::class);
    $cache = $cache->reveal();

    $manager = new CacheManager();
    $manager->addCache($cache);

    $this->assertSame($cache, $manager->getCache(CacheInterface::class));
    $this->assertNull($manager->getCache(Cache::class));
  }

}
