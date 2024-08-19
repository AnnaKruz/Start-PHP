<?php

namespace Geekbrains\Application1\Domain\Models;

class User
{
    private ?int $id_user;
    private ?string $user_name;
    private ?string $user_lastname;
    private ?int $user_birthday_timestamp;
    private ?string $login;
    private ?string $hash_password;
    private ?array $roles;
    private ?string $token;

    public function __construct(string $name = null, string $lastName = null, int $birthday = null,
                                string $login = null, string $hash_password = null,
                                string $token = null, int $id_user = null)
    {
        $this->user_name = $name;
        $this->user_lastname = $lastName;
        $this->user_birthday_timestamp = $birthday;
        $this->id_user = $id_user;
        $this->login = $login;
        $this->hash_password = $hash_password;
        $this->token = $token;
        $this->roles = [];
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(?string $login): void
    {
        $this->login = $login;
    }

    public function getHashPassword(): ?string
    {
        return $this->hash_password;
    }

    public function setHashPassword(?string $hash_password): void
    {
        $this->hash_password = $hash_password;
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

    /** Валидация логина
     * @param string $login
     * @return bool
     */
    public static function validateLogin(string $login): bool
    {
        $pattern = '/^(?=.*\S)(?!<.*>).{3,20}$/';
        return preg_match($pattern, $login) && !preg_match('/\s/', $login);
    }

    /** Проверка надёжности пароля
     * @param string $password
     * @return bool
     */
    public static function validatePassword(string $password): bool
    {
        $pattern = '/^(?=.*\d)(?=.*\w)(?=.*\S)(?!\s).{8,16}$/';
        return mb_strtolower($password) !== 'pass' &&
            mb_strtolower($password) !== 'password' &&
            preg_match($pattern, $password);
    }

    public function setUserName(?string $user_name): void
    {
        $this->user_name = $user_name;
    }

    public function setUserBirthdayTimestamp(?int $user_birthday_timestamp): void
    {
        $this->user_birthday_timestamp = $user_birthday_timestamp;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(?array $roles): void
    {
        $this->roles = $roles;
    }

    /** Добавить роль
     * @param string $role
     * @return void
     */
    public function addRole(string $role): void
    {
        $this->roles[] = $role;
    }

    /** Удалить роль
     * @param string $role
     * @return void
     */
    public function deleteRole(string $role): void
    {
        array_splice($this->roles, array_search($role, $this->roles), 1);
    }
}