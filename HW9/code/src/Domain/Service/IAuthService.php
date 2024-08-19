<?php

namespace Geekbrains\Application1\Domain\Service;

use Geekbrains\Application1\Domain\Models\User;

interface IAuthService
{
    function setParams(User $user): void;
    function giveToken(User $user): void;
    function logout(int $id): void;
}