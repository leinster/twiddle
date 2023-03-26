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
        $twiddler = new SetTwiddler(0, [1]);
        $this->assertEquals(1, $twiddler->count());
        $this->assertEquals([[]], $twiddler->toArray());
    }

    public function test1Choose1IsExhaustive(): void
    {
        $twiddler = new SetTwiddler(1, ["A"]);
        $this->assertEquals(1, $twiddler->Count());
        $this->assertEquals([["A"]], $twiddler->toArray());
    }

    public function test2Choose1IsExhaustive(): void
    {
        $twiddler = new SetTwiddler(1, ["A", "B"]);
        $this->assertEquals(2, $twiddler->count());
        $this->assertEquals([["B"], ["A"]], $twiddler->toArray());
        foreach ($twiddler as $combination) {
            $this->assertEquals(1, count($combination));
        }
    }

    public function test3Choose2IsExhaustive(): void
    {
        $twiddler = new SetTwiddler(2, ["A", "B", "C"]);
        $this->assertEquals(3, $twiddler->count());
        $this->assertEquals(
            [["B", "C"], ["A", "C"], ["A", "B"]],
            $twiddler->toArray()
        );
        foreach ($twiddler as $combination) {
            $this->assertEquals(2, count($combination));
        }
    }

    public function test26Choose6IsExhaustive(): void
    {
        $letters = str_split("ABCDEFGHIJKLMNOPQRSTUVWXYZ");
        $twiddler = new SetTwiddler(6, $letters, stringTransformer());
        $this->assertEquals(230_230, $twiddler->count());
        $combinations = $twiddler->toArray();
        $this->assertEquals("UVWXYZ", $combinations[0]);
        $this->assertEquals($combinations, array_unique($combinations));
        $this->assertEquals($twiddler->count(), count($combinations));
    }
}
