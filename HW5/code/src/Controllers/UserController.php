<?php

namespace Geekbrains\Application1\Controllers;

use Geekbrains\Application1\Application;
use Geekbrains\Application1\Render;
use Geekbrains\Application1\Models\User;

class UserController
{
    private Render $render;

    public function __construct()
    {
        $this->render = new Render();
    }

    /**
     * Добавление пользователя через POST-запрос по форме
     * @return string
     */
    public function actionNew(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['name'] && $_POST['birthday']) {
            return $this->newUser($_POST['name'], $_POST['birthday']);
        } else {
            return "Ты как сюда попал?";
        }
    }

    /**
     * Добавление пользователя через аргументы url
     * @return string
     */
    public function actionSave(): string
    {
        $args = [];
        $queryArray = explode('&', $_SERVER['QUERY_STRING']);
        if (count($queryArray) === 2) {
            foreach ($queryArray as $arg) {
                $res = explode('=', $arg);
                $args[$res[0]] = urldecode($res[1]);
            }
        } else {
            return $this->render->renderPage(
                'message.twig',
                [
                    'title' => "Некорректный ввод",
                    'message' => "Введено неправильное количество аргументов url-запроса"
                ]);
        }

        if (User::validateName($args['name']) && User::validateDate($args['birthday'])) {
            return $this->newUser($args['name'], $args['birthday']);
        } else {
            return $this->render->renderPage(
                'message.twig',
                [
                    'title' => "Некорректный ввод",
                    'message' => "Данные введены некорректно"
                ]);
        }
    }

    /**
     * Форма добавления пользователя
     * @return string
     */
    public function actionAdd(): string
    {
        return $this->render->renderPage(
            'user-add.twig',
            [
                'title' => 'Добавление пользователя',
            ]);
    }

    /**
     * Список пользователей
     * @return string
     */
    public function actionIndex(): string
    {
        $config = Application::config();
        $users = User::getAllUsersFromStorage($config['storage']['address']);

        if (!$users) {
            return $this->render->renderPage(
                'user-empty.twig',
                [
                    'title' => 'Список пользователей в хранилище',
                    'message' => "Список пуст или не найден",
                    'href' => '/user/add'
                ]);
        } else {
            return $this->render->renderPage(
                'user-index.twig',
                [
                    'title' => 'Список пользователей в хранилище',
                    'users' => $users,
                    'href' => '/user/add'
                ]);
        }
    }

    /**
     * Вспомогательная функция добавления пользователя
     * @param string $name
     * @param string $birthday
     * @return string
     */
    private function newUser(string $name, string $birthday): string
    {
        $user = new User($name, strtotime($birthday));
        if ($user->save()) {
            return $this->render->renderPage(
                'message.twig',
                [
                    'title' => "Пользователь добавлен",
                    'message' => "Пользователь {$name} добавлен"
                ]);
        } else {
            return $this->render->renderPage(
                'message.twig',
                [
                    'title' => "Ошибка записи",
                    'message' => "Ошибка записи. Пользователь {$name} не добавлен"
                ]);
        }
    }
}