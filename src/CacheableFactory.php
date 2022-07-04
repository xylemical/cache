<?php

declare(strict_types=1);

namespace Xylemical\Cache;

/**
 * Provides a service collector cacheable factory.
 */
class CacheableFactory implements CacheableFactoryInterface {

  /**
   * The cacheable factories.
   *
   * @var \Xylemical\Cache\CacheableFactoryInterface[]
   */
  protected array $factories = [];

  /**
   * {@inheritdoc}
   */
  public function create(array $contents): ?CacheableInterface {
    foreach ($this->factories as $factory) {
      if ($cacheable = $factory->create($contents)) {
        return $cacheable;
      }
    }
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function contents(CacheableInterface $cacheable): ?array {
    foreach ($this->factories as $factory) {
      if (!is_null($contents = $factory->contents($cacheable))) {
        return $contents;
      }
    }
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function key(CacheableInterface $cacheable): string {
    foreach ($this->factories as $factory) {
      if ($key = $factory->key($cacheable)) {
        return $key;
      }
    }
    return '';
  }

  /**
   * Add a cacheable factory.
   *
   * @param \Xylemical\Cache\CacheableFactoryInterface $factory
   *   The cacheable factory.
   *
   * @return $this
   */
  public function addFactory(CacheableFactoryInterface $factory): static {
    $this->factories[] = $factory;
    return $this;
  }

}
