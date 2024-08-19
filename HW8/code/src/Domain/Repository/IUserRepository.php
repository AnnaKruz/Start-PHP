<?php

namespace Geekbrains\Application1\Domain\Repository;

use Geekbrains\Application1\Domain\Models\User;

interface IUserRepository
{
    function getAllUsers(): array|false;
    function save(User $user): User;
    function getById(int $id) : User|null;
    function getByLogin(string $login) : User|null;
    function update(User $user): User;
    function delete(int $id): bool;
    function getByToken(string $token) : User|null;
}