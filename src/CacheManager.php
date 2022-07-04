<?php

declare(strict_types=1);

namespace Xylemical\Cache;

/**
 * Provides a generic cache manager.
 */
class CacheManager implements CacheManagerInterface {

  /**
   * The caches.
   *
   * @var \Xylemical\Cache\CacheInterface[]
   */
  protected array $caches = [];

  /**
   * {@inheritdoc}
   */
  public function getCache(string $class): ?CacheInterface {
    return $this->caches[$class] ?? NULL;
  }

  /**
   * Add a cache.
   *
   * @param \Xylemical\Cache\CacheInterface $cache
   *   The cache.
   *
   * @return $this
   */
  public function addCache(CacheInterface $cache): static {
    $this->caches[$cache->getType()] = $cache;
    return $this;
  }

}
