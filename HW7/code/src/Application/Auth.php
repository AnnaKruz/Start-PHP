<?php

namespace Geekbrains\Application1\Application;

use Geekbrains\Application1\Domain\Models\User;

/**
 * Аутентификация
 */
class Auth
{
    /** Получить хэш пароля
     * @param string $password
     * @return string
     */
    public function getPasswordHash(string $password): string {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /** Установить параметры сессии
     * @param User $user
     * @return void
     */
    public function setParams(User $user): void
    {
        $_SESSION['login'] = $user->getLogin();
        $_SESSION['password'] = $user->getHashPassword();
        $_SESSION['user_name'] = $user->getUserName();
        $_SESSION['user_lastname'] = $user->getUserLastname();
        $_SESSION['user_birthday_timestamp'] = $user->getUserBirthdayTimestamp();
        $_SESSION['id_user'] = $user->getIdUser();
        $_SESSION['roles'] = $user->getRoles();
    }
}