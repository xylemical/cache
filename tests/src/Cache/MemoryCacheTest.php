<?php

declare(strict_types=1);

namespace Xylemical\Cache\Cache;

use Xylemical\Cache\CacheableFactoryInterface;
use Xylemical\Cache\CacheInterface;
use Xylemical\Time\Current;

/**
 * Tests \Xylemical\Cache\Cache\MemoryCache.
 */
class MemoryCacheTest extends CacheTestCase {

  /**
   * {@inheritdoc}
   */
  protected function getCacheUnderTest(string $type, CacheableFactoryInterface $factory, Current $current): CacheInterface {
    return new MemoryCache($type, $factory, $current);
  }

}
