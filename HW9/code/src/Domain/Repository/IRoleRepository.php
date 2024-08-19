<?php

namespace Geekbrains\Application1\Domain\Repository;

interface IRoleRepository
{
    function findUserRoles(int $id): bool|array;
}