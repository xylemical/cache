<?php

declare(strict_types=1);

namespace Xylemical\Cache;

/**
 * Provides for an object to be cacheable.
 */
interface CacheableInterface {

  /**
   * Get all the dependencies for the cacheable object.
   *
   * @return array
   *   The dependencies.
   */
  public function getCacheDependencies(): array;

  /**
   * Get the cache max age.
   *
   * @return int
   *   The max age.
   */
  public function getCacheMaxAge(): int;

  /**
   * Merges the cacheable object.
   *
   * @param \Xylemical\Cache\CacheableInterface $cacheable
   *   The cacheable.
   *
   * @return $this
   */
  public function mergeCacheable(CacheableInterface $cacheable): static;

}
