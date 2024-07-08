<?php

function mathematic(int|float $val1, int|float $val2, string $operation) : int|float|string
{
    return match ($operation) {
        "+" => $val1 + $val2,
        "-" => $val1 - $val2,
        "*" => $val1 * $val2,
        "/" => $val2 !== 0 ? $val1 / $val2 : "Division by zero error!",
        default => "Unknown operation"
    };
}

echo mathematic(2, 3, "+") . "\n";
echo mathematic(5, 3, "-") . "\n";
echo mathematic(2, 3, "*") . "\n";
echo mathematic(6, 3, "/") . "\n";
echo mathematic(6, 0, "/") . "\n";
echo mathematic(2, 3, "%") . "\n";
