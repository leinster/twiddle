<?php
declare(strict_types=1);

namespace Leinster\Twiddle;

use function Leinster\Twiddle\Functions\{identityTransformer};

use Closure;
use IteratorAggregate;
use Traversable;

// TWIDDLE case (1), generating all combinations of $k out of $n objects. We
// generate all $k-combinations of the $n length array $set.
//
/** @implements \IteratorAggregate<int, mixed[]> */
readonly final class SetTwiddler implements IteratorAggregate
{
    private int $n;
    private Twiddler $twiddler;
    private Closure $transformer;

    /** @param mixed[] $set */
    function __construct(
        private int $k,
        private array $set,
        callable $transformer = null
    ) {
        $this->n = count($set);
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
        $combination = array_slice($this->set, $this->n - $this->k);
        yield ($this->transformer)($combination);

        foreach ($this->twiddler as [$x, $y, $z]) {
            $combination[$z] = $this->set[$x];
            yield ($this->transformer)($combination);
        }
    }

    /** @return mixed[] */
    public function toArray(): array
    {
        return iterator_to_array($this->getIterator());
    }
}
