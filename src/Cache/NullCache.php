<?php

declare(strict_types=1);

namespace Xylemical\Cache\Cache;

use Xylemical\Cache\Cache;

/**
 * Provides a cache that does no caching.
 */
class NullCache extends Cache {

  /**
   * {@inheritdoc}
   */
  protected function exists(string $key): bool {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  protected function load(string $key): ?array {
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  protected function save(string $key, array $contents, int $maxAge): void {
  }

  /**
   * {@inheritdoc}
   */
  protected function delete(string $key): void {
  }

  /**
   * {@inheritdoc}
   */
  protected function wipe(): void {
  }

  /**
   * {@inheritdoc}
   */
  protected function getExpiry(string $key): ?int {
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty(): bool {
    return TRUE;
  }

}
