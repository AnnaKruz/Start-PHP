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
}