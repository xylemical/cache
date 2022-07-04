<?php

declare(strict_types=1);

namespace Xylemical\Cache;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Xylemical\Time\Current;

/**
 * Tests \Xylemical\Cache\Cache.
 */
class CacheTest extends TestCase {

  use ProphecyTrait;

  /**
   * Tests sanity.
   */
  public function testSanity(): void {
    $factory = $this->prophesize(CacheableFactoryInterface::class);
    $factory = $factory->reveal();

    $cache = $this->getMockForAbstractClass(Cache::class, [
      CacheableInterface::class,
      $factory,
      new Current(),
    ]);

    $this->assertEquals(CacheableInterface::class, $cache->getType());
    $this->assertSame($factory, $cache->getFactory());

  }

}
