<?php

// function readAllFunction(string $address) : string {
function readAllFunction(array $config) : string {
    $address = $config['storage']['address'];

    if (file_exists($address) && is_readable($address)) {
        $file = fopen($address, "rb");
        
        $contents = ''; 
    
        while (!feof($file)) {
            $contents .= fread($file, 100);
        }
        
        fclose($file);
        return $contents;
    }
    else {
        return handleError("Файл не существует");
    }
}

// function addFunction(string $address) : string {
function addFunction(array $config) : string {
    $address = $config['storage']['address'];

    $name = readline("Введите имя: ");
    $date = readline("Введите дату рождения в формате ДД-ММ-ГГГГ: ");

    if (validateName($name) && validateDate($date)) {
        $data = $name . ", " . $date . "\r\n";

        $fileHandler = fopen($address, 'a');

        if(fwrite($fileHandler, $data)){
            $result = "Запись $data добавлена в файл $address";
        }
        else {
            $result = handleError("Произошла ошибка записи. Данные не сохранены");
        }
        fclose($fileHandler);
    } else {
        $result = handleError("Некорректный ввод");
    }

    return $result;
}

// function clearFunction(string $address) : string {
function clearFunction(array $config) : string {
    $address = $config['storage']['address'];

    if (file_exists($address) && is_readable($address)) {
        $file = fopen($address, "w");
        
        fwrite($file, '');
        
        fclose($file);
        return "Файл очищен";
    }
    else {
        return handleError("Файл не существует");
    }
}

/**
 * Удалить запись из журнала по имени
 * @param array $config
 * @return string
 */
function delete(array $config) : string
{
    $dataArray = getDataArray($config);

    $name = readline("Введите имя: ");

    if (validateName($name)) {
        for ($i = 0; $i < count($dataArray); $i++) {
            if (explode(', ', $dataArray[$i])[0] === $name) {
                unset($dataArray[$i]);
                $address = $config['storage']['address'];

                $fileHandler = fopen($address, 'w');
                if(fwrite($fileHandler, implode(PHP_EOL, $dataArray) . PHP_EOL)){
                    $result = "Запись удалена";
                }
                else {
                    $result = handleError("Произошла ошибка при удалении из файла.");
                }
                fclose($fileHandler);
                return $result;
            }
        }
    } else {
        return "Имя введено некорректно";
    }

    return "Указанное имя отсутствует в журнале";
}

/**
 * Функция вывода сегодняшних именинников
 * @param array $config
 * @return string
 */
function birthdayToday(array $config) : string
{
    $thisDay = date("d");
    $thisMonth = date("m");

    $dataArray = getDataArray($config);
    $findBirthdays = [];

    foreach ($dataArray as $man) {
        $birthday = explode(', ', $man)[1];
        $dateArr = explode('-', $birthday);
        if ($dateArr[0] === $thisDay && $dateArr[1] === $thisMonth)
            $findBirthdays[] = $man;
    }

    return "Сегодня день рождения у " . implode(PHP_EOL, $findBirthdays);
}

function helpFunction() : string {
    return handleHelp();
}

function readConfig(string $configAddress): array|false{
    return parse_ini_file($configAddress, true);
}