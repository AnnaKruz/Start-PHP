<?php

function power($val, $pow) {
    if ($pow === 1) return $val;
    else return $val * power($val, --$pow);
}

echo power(2, 5);