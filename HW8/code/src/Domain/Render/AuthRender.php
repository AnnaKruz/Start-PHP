<?php

namespace Geekbrains\Application1\Domain\Render;

use Geekbrains\Application1\Application\Render;

class AuthRender implements IAuthRender
{
    private string $prefix = 'auth/';
    private Render $render;
    private array $templates = [
        'auth' => 'auth-login.twig',
        'reg' => 'auth-registration.twig'
    ];

    public function __construct()
    {
        $this->render = new Render();
    }

    /** Отрисовка страницы авторизации
     * @param string $template
     * @param string $title
     * @param string $subtitle
     * @param string $action
     * @param string $login
     * @return string
     */
    public function renderAuthForm(string $templateName, string $title, string $subtitle, string $action,
                                   string $login = "", string $name = "", string $lastname = "",
                                   string|int $birthday = "", string $error_message = ""): string
    {
        return $this->render->renderPage($this->prefix . $this->templates[$templateName],
            [
                'title' => $title,
                'subtitle' => $subtitle,
                'action' => $action,
                'login' => $login,
                'name' => $name,
                'lastname' => $lastname,
                'birthday' => $birthday,
                'error_message' => $error_message
            ]);
    }
}