<?php

declare(strict_types=1);

namespace Xylemical\Cache\Cache;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Xylemical\Cache\CacheableFactoryInterface;
use Xylemical\Cache\CacheableInterface;
use Xylemical\Cache\CacheInterface;
use Xylemical\Cache\CacheManager;
use Xylemical\Cache\CacheMaxAge;
use Xylemical\Time\Current;

/**
 * Provides basic functionality for testing cache behaviour.
 */
abstract class CacheTestCase extends TestCase {

  use ProphecyTrait;

  /**
   * Get the current timestamp.
   *
   * @return int
   *   The timestamp.
   */
  protected function getCurrentTimestamp(): int {
    return strtotime("2020-07-05 12:00:00");
  }

  /**
   * Provides test data for testMaxAge().
   *
   * @return array
   *   The test data.
   */
  public function providerTestMaxAge(): array {
    return [
      [CacheMaxAge::FOREVER, 10, TRUE],
      [CacheMaxAge::FOREVER, 1, TRUE],
      [CacheMaxAge::NEVER, 10, FALSE],
      [CacheMaxAge::NEVER, 1, FALSE],
      [5, 10, FALSE],
      [5, 1, TRUE],
    ];
  }

  /**
   * Creates the cache to test.
   *
   * @param string $type
   *   The type.
   * @param \Xylemical\Cache\CacheableFactoryInterface $factory
   *   The cacheable factory.
   * @param \Xylemical\Time\Current $current
   *   The current time.
   *
   * @return \Xylemical\Cache\CacheInterface
   *   The cache under test.
   */
  abstract protected function getCacheUnderTest(string $type, CacheableFactoryInterface $factory, Current $current): CacheInterface;

  /**
   * Check the cache can save.
   *
   * @return bool
   *   The result.
   */
  protected function canCacheSave(): bool {
    return TRUE;
  }

  /**
   * Tests cache max age.
   *
   * @dataProvider providerTestMaxAge
   */
  public function testMaxAge(int $maxAge, int $shift, bool $expected): void {
    $cacheable = $this->getMockCacheable($maxAge);
    $factory = $this->getMockCacheableFactory('test', ['test' => 'foo'], $cacheable);

    $timestamp = $this->getCurrentTimestamp();
    $current = new Current($timestamp);

    $cache = $this->getCacheUnderTest(CacheManager::class, $factory, $current);
    $this->assertEquals(CacheManager::class, $cache->getType());
    $this->assertSame($factory, $cache->getFactory());
    $this->assertFalse($cache->has(''));
    $this->assertFalse($cache->has('test'));
    $this->assertNull($cache->get('test'));
    $this->assertTrue($cache->isEmpty());
    $this->assertEquals('test', $cache->key($cacheable));

    $unknown = $this->getMockBuilder(CacheableInterface::class)->getMock();
    $this->assertEquals('', $cache->key($unknown));

    $cache->set($cacheable);
    if (!$this->canCacheSave()) {
      $cache->clear();
      $cache->reset('test');
      return;
    }

    if ($maxAge === CacheMaxAge::NEVER) {
      $this->assertFalse($cache->has('test'));
      $this->assertNull($cache->get('test'));
      $this->assertTrue($cache->isEmpty());
      return;
    }

    $this->assertTrue($cache->has('test'));
    $this->assertSame($cacheable, $cache->get('test'));
    $this->assertFalse($cache->isEmpty());

    $current->setCurrentTime($timestamp + $shift);
    $this->assertEquals($expected, $cache->has('test'));
    if ($expected) {
      $this->assertSame($cacheable, $cache->get('test'));

      $cache->reset('test');
      $this->assertFalse($cache->has('test'));
      $this->assertNull($cache->get('test'));
      $this->assertTrue($cache->isEmpty());
    }
    else {
      $this->assertNull($cache->get('test'));
    }
  }

  /**
   * Tests cache clear.
   */
  public function testClear(): void {
    $cacheable = $this->getMockCacheable(10);
    $factory = $this->getMockCacheableFactory('test', ['test' => 'foo'], $cacheable);

    $timestamp = $this->getCurrentTimestamp();
    $current = new Current($timestamp);

    $cache = $this->getCacheUnderTest(CacheManager::class, $factory, $current);
    $this->assertEquals(CacheManager::class, $cache->getType());
    $this->assertSame($factory, $cache->getFactory());
    $this->assertFalse($cache->has(''));
    $this->assertFalse($cache->has('test'));
    $this->assertNull($cache->get('test'));
    $this->assertTrue($cache->isEmpty());

    $cache->set($cacheable);
    if ($this->canCacheSave()) {
      $this->assertTrue($cache->has('test'));
      $this->assertSame($cacheable, $cache->get('test'));
      $this->assertFalse($cache->isEmpty());
    }

    $cache->clear();
    $this->assertFalse($cache->has('test'));
    $this->assertNull($cache->get('test'));
    $this->assertTrue($cache->isEmpty());
  }

  /**
   * Create a mock cacheable factory.
   *
   * @param string $key
   *   The cache key.
   * @param array|null $contents
   *   The contents to be stored.
   * @param \Xylemical\Cache\CacheableInterface|null $cacheable
   *   The cacheable representing the contents.
   *
   * @return \Xylemical\Cache\CacheableFactoryInterface
   *   The cacheable factory.
   */
  protected function getMockCacheableFactory(string $key, ?array $contents = NULL, ?CacheableInterface $cacheable = NULL): CacheableFactoryInterface {
    $factory = $this->prophesize(CacheableFactoryInterface::class);
    $factory->key($cacheable)->willReturn('test');
    $factory->key(Argument::any())->willReturn('');
    $factory->create($contents)->willReturn($cacheable);
    $factory->create(Argument::any())->willReturn(NULL);
    $factory->contents($cacheable)->willReturn($contents);
    $factory->contents(Argument::any())->willReturn(NULL);
    return $factory->reveal();
  }

  /**
   * Get a mock cacheable.
   *
   * @param int $maxAge
   *   The max age of the cacheable.
   *
   * @return \Xylemical\Cache\CacheableInterface
   *   The cacheable.
   */
  protected function getMockCacheable(int $maxAge = CacheMaxAge::FOREVER): CacheableInterface {
    $cacheable = $this->prophesize(CacheableInterface::class);
    $cacheable->getCacheMaxAge()->willReturn($maxAge);
    return $cacheable->reveal();
  }

}
