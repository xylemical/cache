<?php

declare(strict_types=1);

namespace Xylemical\Cache;

/**
 * Provides the serialization of a cacheable.
 */
interface CacheableFactoryInterface {

  /**
   * Creates a cacheable from cache contents.
   *
   * @param array $contents
   *   The cache contents.
   *
   * @return \Xylemical\Cache\CacheableInterface|null
   *   The cacheable or NULL.
   */
  public function create(array $contents): ?CacheableInterface;

  /**
   * Creates the cache contents for the cacheable.
   *
   * @param \Xylemical\Cache\CacheableInterface $cacheable
   *   The cacheable.
   *
   * @return array|null
   *   The cache contents or NULL.
   */
  public function contents(CacheableInterface $cacheable): ?array;

  /**
   * Get the cache key for the creatable.
   *
   * @param \Xylemical\Cache\CacheableInterface $cacheable
   *   The cacheable.
   *
   * @return string
   *   The cache key.
   */
  public function key(CacheableInterface $cacheable): string;

}
