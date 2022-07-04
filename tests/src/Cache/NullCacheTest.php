<?php

declare(strict_types=1);

namespace Xylemical\Cache\Cache;

use Xylemical\Cache\CacheableFactoryInterface;
use Xylemical\Cache\CacheInterface;
use Xylemical\Time\Current;

/**
 * Tests \Xylemical\Cache\Cache\NullCache.
 */
class NullCacheTest extends CacheTestCase {

  /**
   * {@inheritdoc}
   */
  protected function canCacheSave(): bool {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  protected function getCacheUnderTest(string $type, CacheableFactoryInterface $factory, Current $current): CacheInterface {
    return new NullCache($type, $factory, $current);
  }

  /**
   * Tests a function that should never be called.
   */
  public function testCoverage(): void {
    $factory = $this->getMockCacheableFactory('test');
    $current = new Current();
    $cache = new NullCache('', $factory, $current);
    $reflection = new \ReflectionClass($cache);
    $method = $reflection->getMethod('load');
    $method->setAccessible(TRUE);
    $this->assertNull($method->invokeArgs($cache, ['test']));
  }

}
