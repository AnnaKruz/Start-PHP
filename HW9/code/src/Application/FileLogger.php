<?php

namespace Geekbrains\Application1\Application;

use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

/**
 * Логирование в файл
 */
class FileLogger
{
    /** Создать логгер
     * @param string $name
     * @param string $prefix
     * @param string $subDir
     * @return Logger
     */
    public static function createLogger(string $name, string $prefix, string $subDir = ""): Logger
    {
        $dirName = dirname($_SERVER['DOCUMENT_ROOT']) . "/";
        $dirName .= Application::$config->get()['log']['DIR_NAME'];
        if (!empty($subDir)) {
            $dirName .= "/$subDir";
        }
        if (!file_exists($dirName)) {
            mkdir($dirName, 0777, true);
        }
        $date = date('d-m-Y');
        $logger = new Logger($name);
        $logger->pushHandler(new StreamHandler(
            "{$dirName}/{$prefix}-{$date}.log",
            Level::Debug
        ));
        $logger->pushHandler(new FirePHPHandler());
        return $logger;
    }
}