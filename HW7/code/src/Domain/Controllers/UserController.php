<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Exception;
use Geekbrains\Application1\Application\Validator;
use Geekbrains\Application1\Domain\Models\User;
use Geekbrains\Application1\Domain\Render\ISupportRender;
use Geekbrains\Application1\Domain\Render\IUserRender;
use Geekbrains\Application1\Domain\Render\SupportRender;
use Geekbrains\Application1\Domain\Render\UserRender;

class UserController extends Controller
{
    private IUserRender $userRender;
    private ISupportRender $supportRender;
    protected array $actionsPermissions = [
        'actionIndex' => ['admin'],
        'actionNew' => ['admin'],
        'actionRewrite' => ['admin'],
        'actionDelete' => ['admin'],
        'actionAdd' => ['admin'],
        'actionChange' => ['admin'],
        'actionSave' => ['admin'],
        'actionUpdate' => ['admin'],
    ];

    public function __construct()
    {
        parent::__construct();
        $this->userRender = new UserRender();
        $this->supportRender = new SupportRender();
    }

//    region CRUD
    /**
     * Список пользователей
     * @return string
     */
    public function actionIndex(): string
    {
        $users = $this->userService->getAllUsersFromStorage();

        if (!$users) {
            return $this->userRender->renderUsersList("empty");
        } else {
            return $this->userRender->renderUsersList("users", $users);
        }
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
            return $this->newUser($_POST['name'], $_POST['lastname'], $_POST['birthday'],
                $_POST['login'], $_POST['password']);
        } else {
            return "Ты как сюда попал?";
        }
    }

    /**
     * Изменение данных пользователя через форму
     * @throws Exception
     */
    public function actionRewrite(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
            isset($_POST['name']) &&
            isset($_POST['lastname']) &&
            isset($_POST['birthday'])) {
            $user = $this->findUser();
            $user->setUserName($_POST['name']);
            $user->setUserLastname($_POST['lastname']);
            $user->setUserBirthdayTimestamp(strtotime($_POST['birthday']));
            $this->userService->updateUser($user);
            return $this->supportRender->printMessage("Данные пользователя обновлены",
                "Данные пользователя {$user->getUserName()} {$user->getUserLastname()} обновлены");
        } else {
            return "Ты как сюда попал?";
        }
    }

    /** Удаление пользователя
     * @throws Exception
     */
    public function actionDelete(): string
    {
        $user = $this->findUser();
        if ($this->userService->deleteFromStorage($user->getIdUser())) {
            return $this->supportRender->printMessage("Пользователь удалён",
                "Пользователь удалён");
        } else {
            throw new Exception("Ошибка удаления пользователя из базы данных");
        }
    }
//endregion CRUD

//    region forms

    /**
     * Форма добавления нового пользователя
     * @return string
     */
    public function actionAdd(): string
    {
        return $this->userRender->renderAddForm('Добавление пользователя',
            'Добавление нового пользователя', '/user/new');
    }

    /** Форма обновления данных пользователя
     * @throws Exception
     */
    public function actionChange(): string
    {
        $user = $this->findUser();

        return $this->userRender->renderAddForm('Обновление пользователя',
            'Изменение пользовательских данных', "/user/rewrite/?id={$user->getIdUser()}",
            $user->getUserName(), $user->getUserLastname(), $user->getUserBirthdayTimestamp(),
            $user->getLogin());
    }

//endregion forms

//region url-actions

    /**
     * Добавление пользователя через аргументы url
     * @return string
     * @Depricated
     */
    public function actionSave(): string
    {
        if (!isset($_GET['name']) || !isset($_GET['birthday'])) {
            return $this->supportRender->printMessage("Некорректный ввод",
                "Введено неправильное количество аргументов url-запроса");
        }

        if (User::validateName($_GET['name']) &&
            User::validateName($_GET['lastname']) &&
            User::validateDate($_GET['birthday'])) {
            return $this->newUser($_GET['name'], $_GET['lastname'], $_GET['birthday'], $_GET['login'], $_GET['password']);
        } else {
            return $this->supportRender->printMessage("Некорректный ввод",
                "Данные введены некорректно");
        }
    }

    /** Обновление данных пользователя через url
     * @throws Exception
     * @Depricated
     */
    public function actionUpdate(): string
    {
        $user = $this->findUser();

        if (Validator::checkQuery('name') && User::validateName($_GET['name'])) {
            $user->setUserName($_GET['name']);
        }
        if (Validator::checkQuery('lastname')) {
            $user->setUserLastname($_GET['lastname']);
        }
        if (Validator::checkQuery('birthday')) {
            $user->setUserBirthdayTimestamp(strtotime($_GET['birthday']));
        }

        $user = $this->userService->updateUser($user);
        return $this->supportRender->printMessage("Данные обновлены",
            "Данные пользователя {$user->getUserName()} {$user->getUserLastname()} обновлены");
    }

//endregion url-actions

//    region supportFunction
    /** Вспомогательная функция по поиску Юзера
     * @return User
     * @throws Exception
     */
    private function findUser(): User
    {
        $id = Validator::checkId();
        if ($id) {
            return $this->userService->findUserById($id);
        } else {
            throw new Exception("id указан неверно");
        }
    }

    /**
     * Вспомогательная функция добавления пользователя
     * @param string $name
     * @param string $lastname
     * @param string $birthday
     * @param string $login
     * @param string $password
     * @return string
     */
    private function newUser(string $name, string $lastname, string $birthday,
                             string $login, string $password): string
    {
        try {
            foreach ([$name, $lastname, $birthday, $login, $password] as $requestData) {
                if (Validator::validateRequestData($requestData)) {
                    throw new Exception("Попытка отправки тегов");
                }
            }
            $user = $this->userService->createUser($name, $lastname, $birthday, $login, $password);
            return $this->supportRender->printMessage("Пользователь добавлен",
                "Пользователь {$user->getUserName()} {$user->getUserLastname()} добавлен");
        } catch (Exception $e) {
            return $this->supportRender->printMessage("Пользователь не добавлен", $e->getMessage());
        }
    }
//endregion region supportFunction
}