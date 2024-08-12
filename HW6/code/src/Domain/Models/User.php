<?php

namespace Geekbrains\Application1\Domain\Models;

use Exception;
use Geekbrains\Application1\Application\Application;
use Geekbrains\Application1\Application\QueryChecker;
use \PDO;

class User
{
    private ?int $id_user;
    private ?string $user_name;
    private ?string $user_lastname;
    private ?int $user_birthday_timestamp;


    public function __construct(string $name = null, string $lastName = null, int $birthday = null, int $id_user = null)
    {
        $this->user_name = $name;
        $this->user_lastname = $lastName;
        $this->user_birthday_timestamp = $birthday;
        $this->id_user = $id_user;
    }

    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    public function setIdUser(?int $id_user): void
    {
        $this->id_user = $id_user;
    }

    public function getUserLastname(): ?string
    {
        return $this->user_lastname;
    }

    public function setUserLastname(?string $user_lastname): void
    {
        $this->user_lastname = $user_lastname;
    }

    public function setName(string $userName): void
    {
        $this->user_name = $userName;
    }

    public function getUserName(): string
    {
        return $this->user_name;
    }

    public function getUserBirthdayTimestamp(): int
    {
        return $this->user_birthday_timestamp;
    }

    public function setBirthdayFromString(string $birthdayString): void
    {
        $this->user_birthday_timestamp = strtotime($birthdayString);
    }

    public static function getAllUsersFromStorage(): array|false
    {
        $sql = "SELECT * FROM users";

        $storage = Application::$storage->get();
        $handler = $storage->query($sql);
        $handler->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Geekbrains\Application1\Domain\Models\User');
        return $handler->fetchAll();
    }

    /**
     * Валидация даты
     * @param string $date
     * @return bool
     */
    public static function validateDate(string $date): bool
    {
        $dateBlocks = explode("-", $date);

        if (count($dateBlocks) < 3) {
            return false;
        }

        if (isset($dateBlocks[0]) && $dateBlocks[0] > 31 || $dateBlocks[0] < 1) {
            return false;
        }

        if (isset($dateBlocks[1]) && $dateBlocks[1] > 12 || $dateBlocks[1] < 1) {
            return false;
        }

        if (isset($dateBlocks[2]) && $dateBlocks[2] > date('Y') && $dateBlocks[2] < 1900) {
            return false;
        }

        return true;
    }

    /**
     * Валидация по имени
     * @param string $name
     * @return bool
     */
    public static function validateName(string $name): bool
    {
        if (strlen($name) === 0) return false;
        $arr = [];
        for ($i = 0; $i < strlen($name); $i++) {
            $arr[] = $name[$i];
        }
        if (array_intersect([0, 1, 2, 3, 4, 5, 6, 7, 8, 9, '=', '+', '/', '*', '~', '?', '|', '\\', '<', '>', '{', '}', '[', ']', ':', ';', '!'], $arr)) return false;

        return true;
    }

    /**
     * Сохранение нового пользователя
     * @return bool
     */
    public function save(): bool
    {
        $sql = 'INSERT INTO users (user_name, user_lastname, user_birthday_timestamp) 
    VALUE (:user_name, :user_lastname, :user_birthday_timestamp)';
        $params = [
            "user_name" => $this->user_name,
            "user_lastname" => $this->user_lastname,
            "user_birthday_timestamp" => $this->user_birthday_timestamp
        ];
        self::executeQuery($sql, $params);
        $this->setIdUser(Application::$storage->get()->lastInsertId());
        return true;
    }

    public static function isExist(int $id): bool
    {
        return self::getById($id) != '';
    }

    public function setUserName(?string $user_name): void
    {
        $this->user_name = $user_name;
    }

    public function setUserBirthdayTimestamp(?int $user_birthday_timestamp): void
    {
        $this->user_birthday_timestamp = $user_birthday_timestamp;
    }

    public static function getById(int $id) : User|string
    {
        $sql = "SELECT * FROM users WHERE id_user = :id";
        $params = ["id" => $id];
        $handler = self::executeQuery($sql, $params);
        $handler->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Geekbrains\Application1\Domain\Models\User');
        return $handler->fetch();
    }

    public function updateInStorage(): bool
    {
        $sql = 'UPDATE users 
        SET user_name = :name, 
            user_lastname = :lastname, 
            user_birthday_timestamp = :birthday 
        WHERE id_user = :id';
        $params = [
            "name" => $this->user_name,
            "lastname" => $this->user_lastname,
            "birthday" => $this->user_birthday_timestamp,
            "id" => $this->id_user
        ];
        self::executeQuery($sql, $params);
        return true;
    }

    public static function executeQuery(string $sql, array $params): bool|\PDOStatement
    {
        $storage = Application::$storage->get();
        $handler = $storage->prepare($sql);
        $handler->execute($params);
        return $handler;
    }

    public function deleteFromStorage(): bool
    {
        $sql = 'DELETE FROM users WHERE id_user = :id';
        $params = ["id" => $this->id_user];
        self::executeQuery($sql, $params);
        return true;
    }

    /**
     * @throws Exception
     */
    public static function findUser(): User|string
    {
        $id = QueryChecker::checkId();
        if ($id) {
            if (User::isExist($_GET['id'])) {
                return User::getById($id);
            } else {
                throw new Exception("Пользователь с заданным id не существует");
            }
        } else {
            throw new Exception("id указан неверно");
        }
    }
}