<?php

declare(strict_types=1);

namespace Leinster\Twiddle\Tests;

use Leinster\Twiddle\{BitTwiddler, Exception};
use PHPUnit\Framework\TestCase;

use function Leinster\Twiddle\Functions\{intTransformer, stringTransformer};

final class BitTwiddlerTest extends TestCase
{
    public function testKMustBePositive(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("k must be a positive integer");
        new BitTwiddler(-1, 1);
    }

    public function testKMustBeLessThanOrEqualToN(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("k must be less than or equal to n");
        new BitTwiddler(2, 1);
    }

    public function testNMustBeGreaterThanOrEqualTo1(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("n must be greater than");
        new BitTwiddler(1, 0);
    }

    public function test1Choose0IsExhaustive(): void
    {
        $bitTwiddler = new BitTwiddler(0, 1);
        $this->assertEquals(1, $bitTwiddler->count());
        $this->assertEquals([[0]], $bitTwiddler->toArray());
    }

    public function test1Choose1IsExhaustive(): void
    {
        $bitTwiddler = new BitTwiddler(1, 1);
        $this->assertEquals(1, $bitTwiddler->Count());
        $this->assertEquals([[1]], $bitTwiddler->toArray());
    }

    public function test2Choose1IsExhaustive(): void
    {
        $twiddler = new BitTwiddler(1, 2, stringTransformer());
        $this->assertEquals(2, $twiddler->count());
        $this->assertEquals(["01", "10"], $twiddler->toArray());

        $twiddler = new BitTwiddler(1, 2, intTransformer());
        $this->assertEquals(2, $twiddler->count());
        $this->assertEquals([1, 2], $twiddler->toArray());
    }

    public function test3Choose2IsExhaustive(): void
    {
        $twiddler = new BitTwiddler(2, 3, stringTransformer());
        $this->assertEquals(3, $twiddler->count());
        $this->assertEquals(["011", "101", "110"], $twiddler->toArray());

        $twiddler = new BitTwiddler(2, 3, intTransformer());
        $this->assertEquals([3, 5, 6], $twiddler->toArray());
    }

    public function test26Choose6IsExhaustive(): void
    {
        $bitTwiddler = new BitTwiddler(6, 26, intTransformer());
        $this->assertEquals(230_230, $bitTwiddler->count());
        $combinations = $bitTwiddler->toArray();
        $this->assertEquals(
            $combinations,
            array_unique($combinations, SORT_NUMERIC),
        );
        $this->assertEquals($bitTwiddler->count(), count($combinations));
    }
}
