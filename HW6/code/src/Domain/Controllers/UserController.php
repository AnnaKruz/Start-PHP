<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Exception;
use Geekbrains\Application1\Application\Application;
use Geekbrains\Application1\Application\QueryChecker;
use Geekbrains\Application1\Application\Render;
use Geekbrains\Application1\Domain\Models\User;

class UserController
{
    private Render $render;
    private string $prefix = 'user/';

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
        if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
            isset($_POST['name']) &&
            isset($_POST['lastname']) &&
            isset($_POST['birthday'])) {
            return $this->newUser($_POST['name'], $_POST['lastname'], $_POST['birthday']);
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
        if (!isset($_GET['name']) || !isset($_GET['birthday'])) {
            return $this->render->renderPage(
                "{$this->prefix}message.twig",
                [
                    'title' => "Некорректный ввод",
                    'message' => "Введено неправильное количество аргументов url-запроса"
                ]);
        }

        if (User::validateName($_GET['name']) && User::validateDate($_GET['birthday'])) {
            return $this->newUser($_GET['name'], $_GET['lastname'], $_GET['birthday']);
        } else {
            return $this->render->renderPage(
                $this->prefix . 'message.twig',
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
            $this->prefix . 'user-add.twig',
            [
                'title' => 'Добавление пользователя',
                'subtitle' => 'Добавление нового пользователя',
                'action' => '/user/new',
                'name' => "",
                'lastname' => "",
                'birthday' => "",
            ]);
    }

    /**
     * Список пользователей
     * @return string
     */
    public function actionIndex(): string
    {
        $users = User::getAllUsersFromStorage();

        if (!$users) {
            return $this->render->renderPage(
                $this->prefix . 'user-empty.twig',
                [
                    'title' => 'Список пользователей в хранилище',
                    'message' => "Список пользователей пуст",
                    'href' => '/user/add'
                ]);
        } else {
            return $this->render->renderPage(
                $this->prefix . 'user-index.twig',
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
     * @param string $lastname
     * @param string $birthday
     * @return string
     */
    private function newUser(string $name, string $lastname, string $birthday): string
    {
        $user = new User($name, $lastname, strtotime($birthday));
        if ($user->save()) {
            return $this->render->renderPage(
                'support/message.twig',
                [
                    'title' => "Пользователь добавлен",
                    'message' => "Пользователь $name $lastname добавлен"
                ]);
        } else {
            return $this->render->renderPage(
                'support/message.twig',
                [
                    'title' => "Ошибка записи",
                    'message' => "Ошибка записи. Пользователь $name $lastname не добавлен"
                ]);
        }
    }

    /**
     * @throws Exception
     */
    public function actionUpdate(): string
    {
        $user = User::findUser();
        if (QueryChecker::checkQuery('name')) {
            $user->setUserName($_GET['name']);
        }
        if (QueryChecker::checkQuery('lastname')) {
            $user->setUserName($_GET['lastname']);
        }
        if (QueryChecker::checkQuery('birthday')) {
            $user->setUserName($_GET['birthday']);
        }
        if ($user->updateInStorage()) {
            return $this->render->renderPage(
                'support/message.twig',
                [
                    'title' => "Пользователь обновлён",
                    'message' => "Данные обновлены"
                ]);
        } else {
            throw new Exception("Ошибка обновления базы данных");
        }
    }

    /**
     * @throws Exception
     */
    public function actionDelete(): string
    {
        $user = User::findUser();
        if ($user->deleteFromStorage()) {
            return $this->render->renderPage(
                'support/message.twig',
                [
                    'title' => "Пользователь удалён",
                    'message' => "Пользователь удалён"
                ]);
        } else {
            throw new Exception("Ошибка удаления пользователя из базы данных");
        }
    }

    /**
     * @throws Exception
     */
    public function actionChange(): string
    {
        $user = User::findUser();

        return $this->render->renderPage(
            $this->prefix . 'user-add.twig',
            [
                'title' => 'Обновление пользователя',
                'subtitle' => 'Изменение пользовательских данных',
                'action' => "/user/rewrite/?id={$user->getIdUser()}",
                'name' => $user->getUserName(),
                'lastname' => $user->getUserLastname(),
                'birthday' => $user->getUserBirthdayTimestamp(),
            ]);
    }

    /**
     * @throws Exception
     */
    public function actionRewrite(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
            isset($_POST['name']) &&
            isset($_POST['lastname']) &&
            isset($_POST['birthday'])) {
            $user = User::findUser();
            $user->setUserName($_POST['name']);
            $user->setUserLastname($_POST['lastname']);
            $user->setUserBirthdayTimestamp(strtotime($_POST['birthday']));
            $user->updateInStorage();
            return $this->render->renderPage(
                'support/message.twig',
                [
                    'title' => "Данные пользователя обновлены",
                    'message' => "Данные пользователя обновлены"
                ]);
        } else {
            return "Ты как сюда попал?";
        }
    }
}