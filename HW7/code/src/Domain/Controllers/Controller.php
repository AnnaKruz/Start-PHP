<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Exception;
use Geekbrains\Application1\Application\Application;
use Geekbrains\Application1\Domain\Service\IUserService;
use Geekbrains\Application1\Domain\Service\UserService;

class Controller
{
    protected array $actionsPermissions = [];
    protected IUserService $userService;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->userService = new UserService();
        $this->setParams();
    }

    public function getUserRoles(): array
    {
        $roles = [];
        $roles[] = 'user';

        if (isset($_SESSION['id_user'])) {
//            $userRoles = $this->userService->getUserRoleById((int)$_SESSION['id_user']);
//            if($userRoles){
//                foreach ($userRoles as $userRole) {
            foreach ($_SESSION['roles'] as $data) {
                $roles[] = $data['role'];
            }
//            }
        }

        return $roles;
    }

    public function getActionsPermissions(string $methodName): array
    {
        return $this->actionsPermissions[$methodName] ?? [];
    }

    /** Установка параметров
     * @throws Exception
     */
    private function setParams(): void
    {
        if (empty($_SESSION) && isset($_COOKIE['token'])) {
            $user = $this->userService->findUserByToken($_COOKIE['token']);
            Application::$auth->setParams($user);
        }
        if (isset($_SESSION['id_user'])) {

        }
    }
}