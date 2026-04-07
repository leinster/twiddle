<?php

declare(strict_types=1);

namespace Leinster\Twiddle;

use function Leinster\Twiddle\Functions\identityTransformer;

// TWIDDLE case (1), generating all combinations of $k out of $n objects. We
// generate all $k-combinations of the $n length array $set.
//
/**
 * @template T
 * @template R
 *
 * @implements \IteratorAggregate<int, R>
 */
final readonly class SetTwiddler implements \IteratorAggregate
{
    private int $n;

    private Twiddler $twiddler;

    /**
     * @var \Closure(T[]): R
     */
    private \Closure $transformer;

    /**
     * @param T[]                     $set
     * @param null|(callable(T[]): R) $transformer
     */
    public function __construct(
        private int $k,
        private array $set,
        ?callable $transformer = null,
    ) {
        $this->n = count($set);
        $this->twiddler = new Twiddler($this->n, $this->k);

        if ($transformer === null) {
            /** @var (callable(T[]): R) */
            $finalTransformer = identityTransformer();
        } else {
            $finalTransformer = $transformer;
        }

        $this->transformer = \Closure::fromCallable($finalTransformer);
    }

    public function count(): float
    {
        return $this->twiddler->count();
    }

    /**
     * @return \Traversable<int, R>
     */
    public function getIterator(): \Traversable
    {
        $this->twiddler->reset();
        $combination = array_slice($this->set, $this->n - $this->k);

        yield ($this->transformer)($combination);

        foreach ($this->twiddler as [$x, $y, $z]) {
            $combination[$z] = $this->set[$x];

            yield ($this->transformer)($combination);
        }
    }

    /**
     * @return R[]
     */
    public function toArray(): array
    {
        return iterator_to_array($this->getIterator());
    }
}
