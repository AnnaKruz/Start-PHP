<?php

namespace Geekbrains\Application1\Domain\Repository;

use Exception;
use Geekbrains\Application1\Application\FileLogger;
use Geekbrains\Application1\Domain\Models\User;
use Monolog\Logger;
use PDO;
use PDOStatement;

/**
 * Класс для работы с таблицей Юзеров в базе данных
 */
class UserRepository extends Repository implements IUserRepository
{
    private Logger $logger;
    protected string $className = 'Geekbrains\Application1\Domain\Models\User';

    public function __construct()
    {
        parent::__construct();
        $this->logger = FileLogger::createLogger("user_repo_logger", "user-log", "user/repo");
    }


    /** Извлечь всех юзеров
     * @param int|null $first
     * @return array|false
     */
    public function getAllUsers(?int $first = null): array|false
    {
        $sql = "SELECT * FROM users";
        $params = [];

        if (isset($first)){
            $sql .= " WHERE id_user > :first";
            $params['first'] = $first;
        }

        $handler = $this->executeQuery($sql, $params);
        $this->setFetchModeToClass($handler);
        return $handler->fetchAll();
    }


    /**
     * Сохранение нового пользователя
     * @param User $user
     * @return User
     */
    public function save(User $user): User
    {
        $sql = 'INSERT INTO users (user_name, user_lastname, user_birthday_timestamp, login, hash_password) 
    VALUE (:user_name, :user_lastname, :user_birthday_timestamp, :login, :hash_password)';
        $params = [
            "user_name" => $user->getUserName(),
            "user_lastname" => $user->getUserLastname(),
            "user_birthday_timestamp" => $user->getUserBirthdayTimestamp(),
            "login" => $user->getLogin(),
            "hash_password" => $user->getHashPassword()
        ];
        $this->executeQuery($sql, $params);
        $user->setIdUser($this->storage->lastInsertId());
        return $user;
    }

    /** Извлечение из БД по id
     * @param int $id
     * @return User|null
     */
    public function getById(int $id) : User|null
    {
        $sql = "SELECT * FROM users WHERE id_user = :id";
        $params = ["id" => $id];
        $handler = $this->executeQuery($sql, $params);
        $this->setFetchModeToClass($handler);
        return $handler->fetch();
    }

    /** Обновить данные пользователя
     * @param User $user
     * @return User
     * @throws Exception
     */
    public function update(User $user): User
    {
        $sql = 'UPDATE users 
        SET user_name = :name, 
            user_lastname = :lastname, 
            user_birthday_timestamp = :birthday, 
            login = :login, 
            hash_password = :hash_password,
            token = :token
        WHERE id_user = :id';
        $params = [
            "name" => $user->getUserName(),
            "lastname" => $user->getUserLastname(),
            "birthday" => $user->getUserBirthdayTimestamp(),
            "login" => $user->getLogin(),
            "hash_password" => $user->getHashPassword(),
            "token" => $user->getToken(),
            "id" => $user->getIdUser()
        ];
        if ($this->executeQuery($sql, $params)) {
            return $user;
        } else {
            $this->logger->error("Ошибка обновления пользователя в БД (id: {$user->getIdUser()})");
            throw new Exception("Ошибка обновления пользователя в БД");
        }
    }

    /** Удаление юзера по id
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $sql = 'DELETE FROM users WHERE id_user = :id';
        $params = ["id" => $id];
        $this->executeQuery($sql, $params);
        return true;
    }

    /** Поиск по имени пользователя
     * @param string $login
     * @return User|null
     * @throws Exception
     */
    public function getByLogin(string $login): User|null
    {
        $sql = "SELECT * FROM users WHERE login = :login";

        try {
            $handler = $this->executeQuery($sql, ["login" => $login]);
            $this->setFetchModeToClass($handler);
            return $handler->fetch();
        } catch (\Throwable) {
            $this->logger->error("Ошибка запроса к базе данных (Login: <$login>)");
            throw new Exception("Пользователь с логином '$login' не найден");
        }
    }

    /** Поиск по токену
     * @param string $token
     * @return User|null
     * @throws Exception
     */
    function getByToken(string $token): User|null
    {
        $sql = "SELECT * FROM users WHERE token = :token";

        try {
            $handler = $this->executeQuery($sql, ["token" => $token]);
            $this->setFetchModeToClass($handler);
            return $handler->fetch();
        } catch (\Throwable) {
            $this->logger->error("Ошибка запроса к базе данных (Login: <$token>)");
            throw new Exception("Ошибка запроса к базе данных");
        }
    }
}