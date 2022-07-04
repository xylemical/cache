<?php

declare(strict_types=1);

namespace Xylemical\Cache;

/**
 * Provides a generic cacheable.
 */
class Cacheable implements CacheableInterface {

  use CacheableTrait;

  /**
   * Cacheable constructor.
   *
   * @param \Xylemical\Cache\CacheableInterface[] $cacheables
   *   The cacheables.
   */
  public function __construct(array $cacheables = []) {
    foreach ($cacheables as $cacheable) {
      $this->mergeCacheable($cacheable);
    }
  }

}
