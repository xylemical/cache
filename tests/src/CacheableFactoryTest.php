<?php

declare(strict_types=1);

namespace Xylemical\Cache;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

/**
 * Tests \Xylemical\Cache\CacheableFactory.
 */
class CacheableFactoryTest extends TestCase {

  use ProphecyTrait;

  /**
   * Tests sanity.
   */
  public function testSanity(): void {
    $a = $this->getMockBuilder(CacheableInterface::class)->getMock();
    $ab = ['foo' => 'bar'];

    $b = $this->getMockBuilder(CacheableInterface::class)->getMock();
    // @phpstan-ignore-next-line
    $b->test = 0;

    $dependency = $this->prophesize(CacheableFactoryInterface::class);
    $dependency->create($ab)->willReturn($a);
    $dependency->create(Argument::any())->willReturn(NULL);
    $dependency->contents($a)->willReturn($ab);
    $dependency->contents(Argument::any())->willReturn(NULL);
    $dependency->key($a)->willReturn('foo');
    $dependency->key(Argument::any())->willReturn('');
    $dependency = $dependency->reveal();

    $factory = new CacheableFactory();
    $factory->addFactory($dependency);

    $this->assertSame($a, $factory->create($ab));
    $this->assertSame($ab, $factory->contents($a));
    $this->assertEquals('foo', $factory->key($a));

    $this->assertNull($factory->create(['test' => 'case']));
    $this->assertNull($factory->contents($b));
    $this->assertEquals('', $factory->key($b));
  }

}
