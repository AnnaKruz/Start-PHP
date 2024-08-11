<?php

namespace Geekbrains\Application1\Models;

use Geekbrains\Application1\Application;

class User
{

    private ?string $userName;
    private ?int $userBirthday;


    public function __construct(string $name = null, int $birthday = null)
    {
        $this->userName = $name;
        $this->userBirthday = $birthday;
    }

    public function setName(string $userName): void
    {
        $this->userName = $userName;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getUserBirthday(): int
    {
        return $this->userBirthday;
    }

    public function setBirthdayFromString(string $birthdayString): void
    {
        $this->userBirthday = strtotime($birthdayString);
    }

    public static function getAllUsersFromStorage(string $storageAddress): array|false
    {
        $address = $_SERVER['DOCUMENT_ROOT'] . $storageAddress;

        if (file_exists($address) && is_readable($address)) {
            $file = fopen($address, "r");

            $users = [];

            while (!feof($file)) {
                $userString = fgets($file);
                $userArray = explode(",", $userString);

                $user = new User(
                    $userArray[0]
                );
                $user->setBirthdayFromString($userArray[1]);

                $users[] = $user;
            }

            fclose($file);

            return $users;
        } else {
            return false;
        }
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
        $config = Application::config();
        $address = $_SERVER['DOCUMENT_ROOT'] . $config['storage']['address'];

        $birthday = date('d-m-Y', $this->userBirthday);
        $data = PHP_EOL . $this->userName . ", " . $birthday;

        $fileHandler = fopen($address, 'a');
        $result = fwrite($fileHandler, $data);
        fclose($fileHandler);

        return $result;
    }
}