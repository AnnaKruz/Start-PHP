<?php

function sum(int|float $num1, int|float $num2) : int|float
{
    return $num1 + $num2;
}

function sub(int|float $num1, int|float $num2) : int|float
{
    return $num1 - $num2;
}

function multi(int|float $num1, int|float $num2) : int|float
{
    return $num1 * $num2;
}

function div(int|float $num1, int|float $num2) : int|float|string
{
    return $num2 !== 0 ? $num1 / $num2 : "Division by zero error!";
}

echo sum(2,3) . PHP_EOL;
echo sub(7,3) . PHP_EOL;
echo multi(3,4) . PHP_EOL;
echo div(10,5) . PHP_EOL;
echo div(7,0) . PHP_EOL;
