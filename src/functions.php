<?php
declare(strict_types=1);

namespace Leinster\Twiddle\Functions;

function binomialCoefficient(int $n, int $k): float
{
    return \MathPHP\Probability\Combinatorics::combinations($n, $k);
}

/** @return callable(mixed[]): mixed[] */
function identityTransformer(): callable
{
    return static fn(array $combination) => $combination;
}

/** @return callable(mixed[]): string */
function stringTransformer(): callable
{
    return static fn(array $combination) => \implode("", $combination);
}

/** @return callable(int[]): (int|float) */
function intTransformer(): callable
{
    return static fn(array $bits) => \bindec(
        \Leinster\Twiddle\Functions\stringTransformer()($bits)
    );
}
