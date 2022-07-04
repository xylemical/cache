<?php

declare(strict_types=1);

namespace Xylemical\Cache;

use Xylemical\Time\CurrentInterface;

/**
 * Provides a generic cache base.
 */
abstract class Cache implements CacheInterface {

  /**
   * The type.
   *
   * @var string
   */
  protected string $type;

  /**
   * The factory.
   *
   * @var \Xylemical\Cache\CacheableFactoryInterface
   */
  protected CacheableFactoryInterface $factory;

  /**
   * The current time.
   *
   * @var \Xylemical\Time\CurrentInterface
   */
  protected CurrentInterface $current;

  /**
   * Cache constructor.
   *
   * @param string $type
   *   The type for the cache.
   * @param \Xylemical\Cache\CacheableFactoryInterface $factory
   *   The factory.
   * @param \Xylemical\Time\CurrentInterface $current
   *   The current time.
   */
  public function __construct(string $type, CacheableFactoryInterface $factory, CurrentInterface $current) {
    $this->type = $type;
    $this->factory = $factory;
    $this->current = $current;
  }

  /**
   * {@inheritdoc}
   */
  public function getType(): string {
    return $this->type;
  }

  /**
   * {@inheritdoc}
   */
  public function getFactory(): CacheableFactoryInterface {
    return $this->factory;
  }

  /**
   * {@inheritdoc}
   */
  public function key(CacheableInterface $cacheable): string {
    return $this->factory->key($cacheable);
  }

  /**
   * {@inheritdoc}
   */
  public function has(string $key): bool {
    if ($key) {
      $this->expire($key);
      return $this->exists($key);
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function get(string $key): ?CacheableInterface {
    if ($key) {
      $this->expire($key);
      if ($this->exists($key) && !is_null($contents = $this->load($key))) {
        return $this->factory->create($contents);
      }
    }
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function set(CacheableInterface $cacheable): static {
    if ($cacheable->getCacheMaxAge() === CacheMaxAge::NEVER) {
      return $this;
    }
    if (!is_null($contents = $this->factory->contents($cacheable))) {
      if ($key = $this->factory->key($cacheable)) {
        $this->save($key, $contents, $cacheable->getCacheMaxAge());
      }
    }
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function reset(string $key): static {
    if ($key && $this->exists($key)) {
      $this->delete($key);
    }
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function clear(): static {
    $this->wipe();
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  abstract public function isEmpty(): bool;

  /**
   * Performs the cache check.
   *
   * @param string $key
   *   The cache key.
   *
   * @return bool
   *   The result.
   */
  abstract protected function exists(string $key): bool;

  /**
   * Performs the cache load.
   *
   * @param string $key
   *   The cache key.
   *
   * @return array|null
   *   The contents or NULL.
   */
  abstract protected function load(string $key): ?array;

  /**
   * Performs the cache save.
   *
   * @param string $key
   *   The cache key.
   * @param array $contents
   *   The contents.
   * @param int $maxAge
   *   The maximum age.
   */
  abstract protected function save(string $key, array $contents, int $maxAge): void;

  /**
   * Performs a cache removal.
   *
   * @param string $key
   *   The cache key.
   */
  abstract protected function delete(string $key): void;

  /**
   * Performs a cache clear.
   */
  abstract protected function wipe(): void;

  /**
   * Get the expiry time for the cache key.
   *
   * @param string $key
   *   The cache key.
   *
   * @return int|null
   *   The cache expiry time.
   */
  abstract protected function getExpiry(string $key): ?int;

  /**
   * Check the expiry for a given entry.
   *
   * @param string $key
   *   The cache key.
   */
  protected function expire(string $key): void {
    if (is_null($expiry = $this->getExpiry($key))) {
      return;
    }
    if ($expiry <= $this->current->getCurrentTime()) {
      $this->delete($key);
    }
  }

}
