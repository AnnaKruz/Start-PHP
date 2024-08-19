<?php

namespace Geekbrains\Application1\Domain\Repository;


class RoleRepository extends Repository implements IRoleRepository
{

    /** Поиск ролей юзера по id
     * @param int $id
     * @return array|false
     */
    public function findUserRoles(int $id): bool|array
    {
        $sql = "SELECT role FROM user_roles WHERE user_id = :id";
        $handler = $this->executeQuery($sql, ['id' => $id]);
        return $handler->fetchAll();
    }
}