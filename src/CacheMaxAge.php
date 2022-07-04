<?php

declare(strict_types=1);

namespace Xylemical\Cache;

/**
 * Provides comparison for cache max age.
 */
class CacheMaxAge {

  /**
   * The cache age is forever.
   */
  public const FOREVER = -1;

  /**
   * The cache age is never.
   */
  public const NEVER = 0;

  /**
   * Compares cache ages to find the proper max age.
   *
   * @param int $a
   *   The first cache max age.
   * @param int $b
   *   The second cache max age.
   *
   * @return int
   *   The maximum cache max age.
   */
  public static function compare(int $a, int $b): int {
    if ($a === self::FOREVER) {
      return $b;
    }
    elseif ($b === self::FOREVER) {
      return $a;
    }
    return min($a, $b);
  }

}
