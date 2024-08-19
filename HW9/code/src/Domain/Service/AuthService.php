<?php

namespace Geekbrains\Application1\Domain\Service;

use Exception;
use Geekbrains\Application1\Domain\Models\User;
use Random\RandomException;

class AuthService implements IAuthService
{
    private IUserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
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

    /** Выдача токенов
     * @param User $user
     * @return void
     * @throws RandomException
     * @throws Exception
     */
    public function giveToken(User $user): void
    {
        $token = $this->generateToken();
        setcookie('token', $token, time() + 3600, '/');
        $user->setToken($token);
        $this->userService->updateUser($user);
    }

    /** Выход пользователя их системы
     * @param int $id
     * @return void
     * @throws RandomException
     * @throws Exception
     */
    public function logout(int $id): void
    {
        setcookie('token', '', time() - 3600, '/');
        $user = $this->userService->findUserById($id);
        $user->setToken($this->generateToken());
        $this->userService->updateUser($user);
        session_destroy();
    }

    /** Получить хэш пароля
     * @param string $password
     * @return string
     */
    public function getPasswordHash(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /** Генерация нового токена
     * @throws RandomException
     */
    private function generateToken(): string
    {
        return hash('sha256', bin2hex(random_bytes(16)));
    }
}