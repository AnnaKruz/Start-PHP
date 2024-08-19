<?php

namespace Geekbrains\Application1\Domain\Render;

use Geekbrains\Application1\Application\Render;
use Geekbrains\Application1\Domain\Models\User;

class UserRender implements IUserRender
{
    private string $prefix = 'user/';
    private Render $render;
    private array $usersListOptions;

    public function __construct()
    {
        $this->render = new Render();
        $this->usersListOptions = [
            "empty" => [
                "template" => "user-empty.twig",
                'message' => "Список пользователей пуст"
            ],
            "users" => [
                "template" => "user-index.twig",
                'message' => ""
            ]
        ];
    }

    /** Отображение формы добавления пользователей
     * @param string $title
     * @param string $subtitle
     * @param string $action
     * @param string $name
     * @param string $lastname
     * @param string|int $birthday
     * @param string $login
     * @return string
     */
    public function renderAddForm(string $title, string $subtitle, string $action, string $name = "",
                                  string $lastname = "", string|int $birthday = "", string $login = ""): string
    {
        return $this->render->renderPage($this->prefix . 'user-add.twig',
            [
                'title' => $title,
                'subtitle' => $subtitle,
                'action' => $action,
                'name' => $name,
                'lastname' => $lastname,
                'birthday' => $birthday,
                'login' => $login
            ]);
    }

    /** Универсальный шаблон отрисовки списка пользователей
     * @param string $mode модификатор списка (empty/users)
     * @param array $users
     * @return string
     */
    public function renderUsersList(string $mode, array $users = []): string
    {
        $data = $this->usersListOptions[$mode];
        return $this->render->renderPage(
            $this->prefix . $data["template"],
            [
                'title' => "Список пользователей",
                'message' => $data["message"],
                'users' => $users,
                'href' => '/user/add',
            ]);
    }
}