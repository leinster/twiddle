<?php
declare(strict_types=1);

namespace Leinster\Twiddle;

use function Leinster\Twiddle\Functions\{
    binomialCoefficient,
    identityTransformer
};

use IteratorAggregate;
use Traversable;

//  Phillip J Chase's TWIDDLE, `Algorithm 382: Combinations of M out of N
//  Objects [G6]', Communications of the Association for Computing Machinery
//  13:6:368 (1970), https://dl.acm.org/doi/10.1145/362384.362502
//
// See the C implementation by Matthew Belmonte (and original license),
// twiddle.c in this directory. Downloaded from
// https://netlib.org/toms-2014-06-10/382, 2023-02-19.
//
// This implementation is largely a transliteration from the C.
//
/** @implements \IteratorAggregate<int, int[]> */
final class Twiddler implements IteratorAggregate
{
    /** @var int[] */
    private array $p;
    private int $x;
    private int $y;
    private int $z;

    public function __construct(private int $n, private int $k)
    {
        $this->validateParameters();
        $this->reset();
    }

    // From the paper, I've used k rather than m.
    //
    // m and n are used only in the initialization of the auxiliary integer
    // array p[0:n+1], which is done in the main program as follows.
    //
    // (It is assumed that 0 <= m <= n and 1 <= n.)
    //
    // p[0] is set equal to n + 1, and p[n+1] is set equal to -2. p[1] through
    // p[n-m] are set equal to 0. p[n-m+1] through p[n] are set equal,
    // respectively, to 1 through m. If m = 0, then set p[1] equal to 1.
    public function reset(): void
    {
        $this->p = array_fill(0, $this->n + 2, 0);
        $this->p[0] = $this->n + 1;
        $this->p[$this->n + 1] = -2;
        for ($i = $this->n - $this->k + 1; $i < $this->n + 1; $i++) {
            $this->p[$i] = $i + $this->k - $this->n;
        }
        if ($this->k === 0) {
            $this->p[1] = 1;
        }
    }

    private function validateParameters(): void
    {
        if ($this->n < 1) {
            throw new Exception("n must be greater than or equal to 1");
        }
        if ($this->k < 0) {
            throw new Exception("k must be a positive integer");
        }
        if ($this->k > $this->n) {
            throw new Exception("k must be less than or equal to n");
        }
    }

    public function count(): float
    {
        return binomialCoefficient($this->n, $this->k);
    }

    public function getIterator(): Traversable
    {
        while (!$this->twiddle()) {
            yield [$this->x, $this->y, $this->z];
        }
    }

    // From the paper, note that x, y, z are 0-indexed in this implementation,
    // as in twiddle.c.
    //
    // begin integer i, j, k; j := 0;
    // L1:
    //   j := j + 1; if p[j] <= 0 then go to L1
    //   if p[j-1] = 0 then
    //   begin
    //     for i := j - 1 step -1 until 2 do p[i] := -1; p[j] = 0;
    //     p[1] := x := z := 1; y := j; go to L4
    //   end;
    //   if j > 1 then p[j - 1] := 0
    // L2:
    //   j := j + 1; if p[j] > 0 then go to L2;
    //   i := k := j - 1;
    // L3:
    //   i := i + 1; if p[i] = 0 then
    //   begin p[i] := -1; go to L3 end;
    //   if p[i] = -1 then
    //   begin
    //     p[i] := z := p[k]; x := i; y := k;
    //     p[k] := -1; go to L4
    //   end;
    //   if i = p[0] then begin done := true; go to L4 end;
    //   z := p[j] := p[i]; p[i] := 0; x := j; y := i;
    // L4:
    // end of TWIDDLE
    private function twiddle(): bool
    {
        // L1
        $j = 1;
        while ($this->p[$j] <= 0) {
            $j++;
        }
        if ($this->p[$j - 1] === 0) {
            for ($i = $j - 1; $i > 1; $i--) {
                $this->p[$i] = -1;
            }
            $this->p[$j] = 0;
            $this->p[1] = 1;
            $this->x = $this->z = 0;
            $this->y = $j - 1;
            return false; // L4
        }
        if ($j > 1) {
            $this->p[$j - 1] = 0;
        }

        // L2
        do {
            $j++;
        } while ($this->p[$j] > 0);
        $i = $j;
        $k = $j - 1;

        // L3
        while ($this->p[$i] === 0) {
            $this->p[$i++] = -1;
        }
        if ($this->p[$i] === -1) {
            $this->p[$i] = $this->p[$k];
            $this->z = $this->p[$k] - 1;
            $this->x = $i - 1;
            $this->y = $k - 1;
            $this->p[$k] = -1;
            return false; // L4
        }

        if ($i === $this->p[0]) {
            return true;
        }

        $this->p[$j] = $this->p[$i];
        $this->z = $this->p[$i] - 1;
        $this->p[$i] = 0;
        $this->x = $j - 1;
        $this->y = $i - 1;

        return false; // L4
    }
}
