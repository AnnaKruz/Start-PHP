<?php

namespace Geekbrains\Application1\Domain\Service;

use Geekbrains\Application1\Domain\Models\User;

interface IUserService
{
    function createUser(string $name, string $lastname, string $birthday,
                        string $login, string $hash_password): User;
    function getAllUsersFromStorage(?int $first): bool|array;
    function findUserById(int $id) : User;
    function findUserByLogin(string $login) : User;
    function updateUser(User $user) : User;
    function deleteFromStorage(int $id) : bool;
    function getUserRoleById(int $id) : array|false;
    function authUser(string $login, string $password) : User|false;
    function findUserByToken(string $token) : User|false;
}