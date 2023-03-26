<?php
declare(strict_types=1);

namespace Leinster\Twiddle;

use function Leinster\Twiddle\Functions\{identityTransformer};

use Closure;
use IteratorAggregate;
use Traversable;

// TWIDDLE case (2), generating all $n-length sequences containing
// $k ones and ($n - $k) zeros.
//
/** @implements \IteratorAggregate<int, int[]> */
readonly final class BitTwiddler implements IteratorAggregate
{
    private Twiddler $twiddler;
    private Closure $transformer;

    public function __construct(
        private int $k,
        private int $n,
        callable $transformer = null
    ) {
        $this->twiddler = new Twiddler($this->n, $this->k);
        $this->transformer = Closure::fromCallable(
            is_null($transformer) ? identityTransformer() : $transformer
        );
    }

    public function count(): float
    {
        return $this->twiddler->count();
    }

    public function getIterator(): Traversable
    {
        $this->twiddler->reset();
        $bits = [
            ...array_fill(0, $this->n - $this->k, 0),
            ...array_fill(0, $this->k, 1),
        ];
        yield ($this->transformer)($bits);

        foreach ($this->twiddler as [$x, $y, $z]) {
            $bits[$x] = 1;
            $bits[$y] = 0;
            yield ($this->transformer)($bits);
        }
    }

    /** @return int[][] */
    public function toArray(): array
    {
        return iterator_to_array($this->getIterator());
    }
}
