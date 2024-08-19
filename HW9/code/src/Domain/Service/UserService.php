<?php

namespace Geekbrains\Application1\Domain\Service;

use Exception;
use Geekbrains\Application1\Application\Application;
use Geekbrains\Application1\Application\Auth;
use Geekbrains\Application1\Application\FileLogger;
use Geekbrains\Application1\Domain\Models\User;
use Geekbrains\Application1\Domain\Repository\IRoleRepository;
use Geekbrains\Application1\Domain\Repository\IUserRepository;
use Geekbrains\Application1\Domain\Repository\RoleRepository;
use Geekbrains\Application1\Domain\Repository\UserRepository;
use Monolog\Logger;

class UserService implements IUserService
{
    private IUserRepository $userRepository;
    private IRoleRepository $roleRepository;
    private Logger $logger;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->roleRepository = new RoleRepository();
        $this->logger = FileLogger::createLogger("user_service_logger", "user-log", "user/service");
    }

    /** Создание нового пользователя
     * @param string $name
     * @param string $lastname
     * @param string $birthday
     * @param string $login
     * @param string $hash_password
     * @return User
     * @throws Exception
     */
    public function createUser(string $name, string $lastname, string $birthday,
                               string $login, string $hash_password): User
    {
        try {
            $user = new User($name, $lastname, strtotime($birthday), $login, $hash_password);
            return $this->userRepository->save($user);
        } catch (Exception) {
            $this->logger->error("Ошибка записи. Пользователь $name $lastname не добавлен");
            throw new Exception("Ошибка записи. Пользователь $name $lastname не добавлен");
        }
    }

    /** Извлечь всех юзеров из БД
     * @return array|false
     */
    public function getAllUsersFromStorage(?int $first = null): bool|array
    {
        return $this->userRepository->getAllUsers($first);
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
            $this->logger->error("Пользователь не найден (id: <$id>)");
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
     * @throws Exception
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
            $this->logger->error("Пароль указан неверно (<$login> : <$password>)");
            throw new Exception("Пароль указан неверно");
        }
    }

    /** Поиск юзера по токену
     * @param string $token
     * @return User|false
     * @throws Exception
     */
    public function findUserByToken(string $token): User|false
    {
        return $this->userRepository->getByToken($token);
    }

    /** Проверить, является ли пользователь админом
     * @param int $id
     * @return bool
     */
    public function isAdmin(int $id): bool
    {
        $roles = $this->roleRepository->findUserRoles($id);
        return in_array("admin", $roles);
    }
}