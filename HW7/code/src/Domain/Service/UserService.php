<?php

namespace Geekbrains\Application1\Domain\Service;

use Exception;
use Geekbrains\Application1\Application\Application;
use Geekbrains\Application1\Application\Auth;
use Geekbrains\Application1\Domain\Models\User;
use Geekbrains\Application1\Domain\Repository\IRoleRepository;
use Geekbrains\Application1\Domain\Repository\IUserRepository;
use Geekbrains\Application1\Domain\Repository\RoleRepository;
use Geekbrains\Application1\Domain\Repository\UserRepository;

class UserService implements IUserService
{
    private IUserRepository $userRepository;
    private IRoleRepository $roleRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->roleRepository = new RoleRepository();
    }

    /** Создание нового пользователя
     * @param string $name
     * @param string $lastname
     * @param string $birthday
     * @param string $login
     * @param string $password
     * @return User
     * @throws Exception
     */
    public function createUser(string $name, string $lastname, string $birthday,
                               string $login, string $password): User
    {
        try {
            $hash_password = Application::$auth->getPasswordHash($password);
            $user = new User($name, $lastname, strtotime($birthday), $login, $hash_password);
            return $this->userRepository->save($user);
        } catch
        (Exception) {
            throw new Exception("Ошибка записи. Пользователь $name $lastname не добавлен");
        }
    }

    /** Извлечь всех юзеров из БД
     * @return array|false
     */
    public function getAllUsersFromStorage(): bool|array
    {
        return $this->userRepository->getAllUsers();
    }

    /** Поиск пользователя в БД по id
     * @param int $id
     * @return User
     * @throws Exception
     */
    public function findUserById(int $id): User
    {
        $user = $this->userRepository->getById($id);
        if ($user) {
            return $user;
        } else {
            throw new Exception("Пользователь не найден");
        }
    }

    /** Обновление данных пользователя в БД
     * @throws Exception
     */
    public function updateUser(User $user): User
    {
        return $this->userRepository->update($user);
    }

    /** Удаление пользователя из БД
     * @param int $id
     * @return bool
     */
    public function deleteFromStorage(int $id): bool
    {
        return $this->userRepository->delete($id);
    }

    /** Поиск пользователя по логину
     * @param string $login
     * @return User
     */
    function findUserByLogin(string $login): User
    {
        return $this->userRepository->getByLogin($login);
    }

    /** Получить из БД роли юзера по его id
     * @param int $id
     * @return array|false
     * @throws Exception
     */
    function getUserRoleById(int $id): array|false
    {
        $user = $this->findUserById($id);
        return $this->roleRepository->findUserRoles($user->getIdUser());
    }

    /** Авторизация пользователя
     * @param string $login
     * @param string $password
     * @return User|false
     * @throws Exception
     */
    public function authUser(string $login, string $password): User|false
    {
        $user = $this->findUserByLogin($login);
        $hash = $user->getHashPassword();
        if (password_verify($password, $hash)) {
            $roles = $this->roleRepository->findUserRoles($user->getIdUser());
            if ($roles) {
                $user->setRoles($roles);
            }
            return $user;
        } else {
            throw new Exception("Пароль указан неверно");
        }
    }

    /** Поиск юзера по токену
     * @param string $token
     * @return User|false
     * @throws Exception
     */
    function findUserByToken(string $token): User|false
    {
        return $this->userRepository->getByToken($token);
    }
}