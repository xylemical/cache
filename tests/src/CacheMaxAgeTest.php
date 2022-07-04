<?php

declare(strict_types=1);

namespace Xylemical\Cache;

use PHPUnit\Framework\TestCase;

/**
 * Tests \Xylemical\Cache\CacheMaxAge.
 */
class CacheMaxAgeTest extends TestCase {

  /**
   * Provides the test data for testCompare().
   *
   * @return array
   *   The test data.
   */
  public function providerTestCompare(): array {
    return [
      [CacheMaxAge::NEVER, CacheMaxAge::FOREVER, CacheMaxAge::NEVER],
      [CacheMaxAge::FOREVER, CacheMaxAge::NEVER, CacheMaxAge::NEVER],
      [CacheMaxAge::FOREVER, CacheMaxAge::FOREVER, CacheMaxAge::FOREVER],
      [CacheMaxAge::NEVER, 1, CacheMaxAge::NEVER],
      [1, CacheMaxAge::NEVER, CacheMaxAge::NEVER],
      [CacheMaxAge::FOREVER, 1, 1],
      [1, CacheMaxAge::FOREVER, 1],
      [1, 2, 1],
      [2, 1, 1],
    ];
  }

  /**
   * Tests sanity.
   *
   * @dataProvider providerTestCompare
   */
  public function testCompare(int $a, int $b, int $expected): void {
    $this->assertEquals($expected, CacheMaxAge::compare($a, $b));
  }

}
