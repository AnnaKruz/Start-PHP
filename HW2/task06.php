<?php

function printCurrentTime(): string
{
    $hoursAndMinutes = explode(":", date('H:i'));

    $hoursPrint = match ($hoursAndMinutes[0] % 10) {
        1 => "час",
        2, 3, 4 => "часа",
        default => "часов",
    };

    $minutesPrint = match ($hoursAndMinutes[1] % 10) {
        1 => "минута",
        2, 3, 4 => "минуты",
        default => "минут",
    };

    return "$hoursAndMinutes[0] $hoursPrint $hoursAndMinutes[1] $minutesPrint";
}

echo printCurrentTime();