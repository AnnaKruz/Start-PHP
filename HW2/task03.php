<?php

$regions = [
    "Московская область" => ["Москва", "Зеленоград", "Клин"],
    "Ленинградская область" => ["Санкт-Петербург", "Всеволожск", "Павловск", "Кронштадт"],
    "Приморский край" => ["Владивосток", "Находка", "Уссурийск", "Артём"]
];

function parsRegions(array $regions) : string
{
    $line = "";
    foreach ($regions as $key => $region) {
        $line .= "$key: ";
        for ($i = 0; $i < count($region) - 1; $i++) {
            $line .= "$region[$i], ";
        }
        $line .= $region[count($region) - 1] . PHP_EOL;
    }

    return $line;
}

echo parsRegions($regions);