<?php

declare(strict_types=1);

namespace Leinster\Twiddle\Functions;

use MathPHP\Probability\Combinatorics;

function binomialCoefficient(int $n, int $k): float
{
    return Combinatorics::combinations($n, $k);
}

/** @return callable(mixed[]): mixed[] */
function identityTransformer(): callable
{
    return static fn(array $combination): array => $combination;
}

/** @return callable(mixed[]): string */
function stringTransformer(): callable
{
    return static fn(array $combination): string => \implode("", $combination);
}

/** @return callable(int[]): (float|int) */
function intTransformer(): callable
{
    return static fn(array $bits): float|int => \bindec(
        stringTransformer()($bits),
    );
}
