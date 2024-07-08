<?php

function translit(string $text): string
{
    $translatium = [
        'а' => 'a',
        'б' => 'b',
        'в' => 'v',
        'г' => 'g',
        'д' => 'd',
        'е' => 'e',
        'ё' => 'yo',
        'ж' => 'zh',
        'з' => 'z',
        'и' => 'i',
        'й' => 'ei',
        'к' => 'k',
        'л' => 'l',
        'м' => 'm',
        'н' => 'n',
        'о' => 'o',
        'п' => 'p',
        'р' => 'r',
        'с' => 's',
        'т' => 't',
        'у' => 'u',
        'ф' => 'f',
        'х' => 'h',
        'ц' => 'ts',
        'ч' => 'ch',
        'ш' => 'sh',
        'щ' => 'sch',
        'ъ' => '',
        'ы' => 'e',
        'ь' => '\'',
        'э' => 'e',
        'ю' => 'yu',
        'я' => 'ya'
    ];

    $result = '';

    foreach (mb_str_split($text) as $char) {
        if (in_array($char, $translatium)) {
            if (mb_strtoupper($char) === $char) {
                $result .= mb_strtoupper($translatium[$char]);
            } else $result .= $translatium[$char];
        } else $result .= $char;
    }

    return $result;
}

echo translit('Привет!') . PHP_EOL;
echo translit('Ехал Грека через реку') . PHP_EOL;