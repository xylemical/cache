<?php

declare(strict_types=1);

namespace Xylemical\Cache;

/**
 * Provides a cache manager.
 */
interface CacheManagerInterface {

  /**
   * Get the cache for a class name.
   *
   * @param string $class
   *   The class/interface/trait name.
   *
   * @return \Xylemical\Cache\CacheInterface|null
   *   The cache.
   */
  public function getCache(string $class): ?CacheInterface;

}
