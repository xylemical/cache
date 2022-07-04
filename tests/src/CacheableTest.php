<?php

declare(strict_types=1);

namespace Xylemical\Cache;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

/**
 * Tests \Xylemical\Cache\Cacheable.
 */
class CacheableTest extends TestCase {

  use ProphecyTrait;

  /**
   * Create a mock cacheable.
   *
   * @param \Xylemical\Cache\CacheableInterface[] $dependencies
   *   The dependencies.
   * @param int $age
   *   The max age.
   *
   * @return \Xylemical\Cache\CacheableInterface
   *   The cacheable.
   */
  protected function getMockCacheable(array $dependencies = [], int $age = CacheMaxAge::FOREVER): CacheableInterface {
    $cacheable = $this->prophesize(CacheableInterface::class);
    $cacheable->getCacheDependencies()->willReturn($dependencies);
    $cacheable->getCacheMaxAge()->willReturn($age);
    return $cacheable->reveal();
  }

  /**
   * Tests sanity.
   */
  public function testSanity(): void {
    $a = $this->getMockCacheable([], 10);
    $b = $this->getMockCacheable([$a], 5);

    $cacheable = new Cacheable([$b]);
    $this->assertEquals([$b, $a], $cacheable->getCacheDependencies());
    $this->assertEquals(5, $cacheable->getCacheMaxAge());
  }

}
