<?php

declare(strict_types=1);

namespace Xylemical\Cache;

use function in_array;

/**
 * Provides an implementation of CacheableInterface.
 */
trait CacheableTrait {

  /**
   * The dependencies.
   *
   * @var \Xylemical\Cache\CacheableInterface[]
   */
  protected array $cacheDependencies = [];

  /**
   * The max age.
   *
   * @var int
   */
  protected int $maxAge = CacheMaxAge::FOREVER;

  /**
   * {@inheritdoc}
   */
  public function getCacheDependencies(): array {
    return $this->cacheDependencies;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge(): int {
    return $this->maxAge;
  }

  /**
   * {@inheritdoc}
   */
  public function mergeCacheable(CacheableInterface $cacheable): static {
    $this->addCacheable($cacheable);
    return $this;
  }

  /**
   * Adds a cacheable dependency.
   *
   * @param \Xylemical\Cache\CacheableInterface $cacheable
   *   The cacheable.
   */
  protected function addCacheable(CacheableInterface $cacheable): void {
    if (!in_array($cacheable, $this->cacheDependencies)) {
      $this->cacheDependencies[] = $cacheable;
      $this->maxAge = CacheMaxAge::compare($this->maxAge, $cacheable->getCacheMaxAge());
      foreach ($cacheable->getCacheDependencies() as $dependency) {
        $this->addCacheable($dependency);
      }
    }
  }

}
