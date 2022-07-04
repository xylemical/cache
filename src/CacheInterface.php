<?php

declare(strict_types=1);

namespace Xylemical\Cache;

/**
 * Provides a cache.
 */
interface CacheInterface {

  /**
   * Get the class/interface/trait type.
   *
   * @return string
   *   The type.
   */
  public function getType(): string;

  /**
   * Get the cacheable factory.
   *
   * @return \Xylemical\Cache\CacheableFactoryInterface
   *   The factory.
   */
  public function getFactory(): CacheableFactoryInterface;

  /**
   * Get the key representing the cacheable.
   *
   * @param \Xylemical\Cache\CacheableInterface $cacheable
   *   The cacheable.
   *
   * @return string
   *   The key.
   */
  public function key(CacheableInterface $cacheable): string;

  /**
   * Check the cache contains the key.
   *
   * @param string $key
   *   The key.
   *
   * @return bool
   *   The result.
   */
  public function has(string $key): bool;

  /**
   * Get a cacheable from the cache.
   *
   * @param string $key
   *   The cache key.
   *
   * @return \Xylemical\Cache\CacheableInterface|null
   *   The object or NULL.
   */
  public function get(string $key): ?CacheableInterface;

  /**
   * Set the cacheable object.
   *
   * @param \Xylemical\Cache\CacheableInterface $cacheable
   *   The cacheable object.
   *
   * @return $this
   */
  public function set(CacheableInterface $cacheable): static;

  /**
   * Resets a cacheable object.
   *
   * @param string $key
   *   The cache key.
   *
   * @return $this
   */
  public function reset(string $key): static;

  /**
   * Clears the entire cache.
   *
   * @return $this
   */
  public function clear(): static;

  /**
   * Check the cache is not empty.
   *
   * @return bool
   *   The result.
   */
  public function isEmpty(): bool;

}
