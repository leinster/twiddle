<?php
declare(strict_types=1);

namespace Leinster\Twiddle\Tests;

use Leinster\Twiddle\{SetTwiddler, Exception};
use function Leinster\Twiddle\Functions\stringTransformer;

use PHPUnit\Framework\TestCase;

final class SetTwiddlerTest extends TestCase
{
    public function testKMustBePositive(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("k must be a positive integer");
        new SetTwiddler(-1, [1, 2, 3]);
    }

    public function testKMustBeLessThanOrEqualToN(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("k must be less than or equal to n");
        new SetTwiddler(2, [1]);
    }

    public function testNMustBeGreaterThanOrEqualTo1(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("n must be greater than");
        new SetTwiddler(1, []);
    }

    public function test1Choose0IsExhaustive(): void
    {
        $setTwiddler = new SetTwiddler(0, [1]);
        $this->assertEquals(1, $setTwiddler->count());
        $this->assertEquals([[]], $setTwiddler->toArray());
    }

    public function test1Choose1IsExhaustive(): void
    {
        $setTwiddler = new SetTwiddler(1, ["A"]);
        $this->assertEquals(1, $setTwiddler->Count());
        $this->assertEquals([["A"]], $setTwiddler->toArray());
    }

    public function test2Choose1IsExhaustive(): void
    {
        $setTwiddler = new SetTwiddler(1, ["A", "B"]);
        $this->assertEquals(2, $setTwiddler->count());
        $this->assertEquals([["B"], ["A"]], $setTwiddler->toArray());
        foreach ($setTwiddler as $combination) {
            $this->assertEquals(1, count($combination));
        }
    }

    public function test3Choose2IsExhaustive(): void
    {
        $setTwiddler = new SetTwiddler(2, ["A", "B", "C"]);
        $this->assertEquals(3, $setTwiddler->count());
        $this->assertEquals(
            [["B", "C"], ["A", "C"], ["A", "B"]],
            $setTwiddler->toArray()
        );
        foreach ($setTwiddler as $combination) {
            $this->assertEquals(2, count($combination));
        }
    }

    public function test26Choose6IsExhaustive(): void
    {
        $letters = str_split("ABCDEFGHIJKLMNOPQRSTUVWXYZ");
        $setTwiddler = new SetTwiddler(6, $letters, stringTransformer());
        $this->assertEquals(230_230, $setTwiddler->count());
        $combinations = $setTwiddler->toArray();
        $this->assertEquals("UVWXYZ", $combinations[0]);
        $this->assertEquals($combinations, array_unique($combinations));
        $this->assertEquals($setTwiddler->count(), count($combinations));
    }
}
