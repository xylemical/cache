<?php

declare(strict_types=1);

namespace Xylemical\Cache\Cache;

use Xylemical\Cache\Cache;
use Xylemical\Cache\CacheMaxAge;

/**
 * Performs memory cache.
 */
class MemoryCache extends Cache {

  /**
   * The cache.
   *
   * @var array[]
   */
  protected array $cache = [];

  /**
   * The timeout.
   *
   * @var int[]
   */
  protected array $timeout = [];

  /**
   * {@inheritdoc}
   */
  protected function exists(string $key): bool {
    return isset($this->cache[$key]);
  }

  /**
   * {@inheritdoc}
   */
  protected function load(string $key): ?array {
    return $this->cache[$key] ?? NULL;
  }

  /**
   * {@inheritdoc}
   */
  protected function save(string $key, array $contents, int $maxAge): void {
    $this->cache[$key] = $contents;
    if ($maxAge !== CacheMaxAge::FOREVER) {
      $this->timeout[$key] = $this->current->getCurrentTime() + $maxAge;
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function delete(string $key): void {
    unset($this->cache[$key]);
    unset($this->timeout[$key]);
  }

  /**
   * {@inheritdoc}
   */
  protected function wipe(): void {
    $this->cache = [];
    $this->timeout = [];
  }

  /**
   * {@inheritdoc}
   */
  protected function getExpiry(string $key): ?int {
    return $this->timeout[$key] ?? NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty(): bool {
    return !count($this->cache);
  }

}
