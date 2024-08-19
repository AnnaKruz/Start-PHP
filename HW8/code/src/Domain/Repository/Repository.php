<?php

namespace Geekbrains\Application1\Domain\Repository;

use Geekbrains\Application1\Application\Application;
use PDO;
use PDOStatement;

class Repository
{
    /**
     * @var PDO База данных
     */
    public PDO $storage;
    protected string $className;

    public function __construct()
    {
        $this->storage = Application::$storage->get();
    }


    /** Выполнение запроса к БД
     * @param string $sql
     * @param array $params
     * @return bool|PDOStatement
     */
    protected function executeQuery(string $sql, array $params = []): bool|PDOStatement
    {
        $handler = $this->storage->prepare($sql);
        $handler->execute($params);
        return $handler;
    }

    /** Установить метод извлечения данных на мапинг по классу
     * @param PDOStatement $handler
     * @return void
     */
    protected function setFetchModeToClass(PDOStatement $handler): void
    {
        $handler->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->className);
    }

}