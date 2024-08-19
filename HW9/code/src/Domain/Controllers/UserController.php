<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Exception;
use Geekbrains\Application1\Application\Application;
use Geekbrains\Application1\Application\FileLogger;
use Geekbrains\Application1\Domain\Models\User;
use Geekbrains\Application1\Domain\Models\Validator\UserValidator;
use Geekbrains\Application1\Domain\Render\ISupportRender;
use Geekbrains\Application1\Domain\Render\IUserRender;
use Geekbrains\Application1\Domain\Render\SupportRender;
use Geekbrains\Application1\Domain\Render\UserRender;
use Monolog\Logger;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class UserController extends Controller
{
    private IUserRender $userRender;
    private ISupportRender $supportRender;
    private UserValidator $userValidator;
    private Logger $logger;
    protected array $actionsPermissions = [
        'actionIndex' => ['user'],
        'actionNew' => ['admin'],
        'actionRewrite' => ['admin'],
        'actionDelete' => ['admin'],
        'actionAdd' => ['admin'],
        'actionChange' => ['admin'],
        'actionSave' => ['admin'],
        'actionUpdate' => ['admin'],
        'actionRefresh' => ['user'],
    ];

    public function __construct()
    {
        parent::__construct();
        $this->userRender = new UserRender();
        $this->supportRender = new SupportRender();
        $this->userValidator = new UserValidator();
        $this->logger = FileLogger::createLogger("user_controller_logger", "user-log", "user/controller");
    }

//    region CRUD

    /**
     * Список пользователей
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function actionIndex(): string
    {
        $isAdmin = null;
        if (isset($_SESSION['id_user'])) {
            $roles = $this->getUserRoles();
            $isAdmin = in_array("admin", $roles);
        }

        $users = $this->userService->getAllUsersFromStorage();

        $mode = !$users ? "empty" : "users";

        return $this->userRender->renderUsersList($mode, $users, $isAdmin);
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
            isset($_POST['birthday']) &&
            isset($_POST['login']) &&
            isset($_POST['password'])) {
            $this->logger->info("Админ <{$_SESSION['login']}> создал нового пользователя 
            (login: <{$_POST['login']}>, name: <{$_POST['name']}>, 
            lastname: <{$_POST['lastname']}>)");
            return $this->newUser($_POST['name'], $_POST['lastname'], $_POST['birthday'],
                $_POST['login'], $_POST['password']);
        } else {
            $this->logger->warning("Подозрительный запрос");
            header('Location: /404.php', true, 404);
            exit();
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
            isset($_POST['birthday']) &&
            isset($_POST['login']) &&
            isset($_POST['password'])) {
            $user = $this->findUser();
            $user->setUserName($_POST['name']);
            $user->setUserLastname($_POST['lastname']);
            $user->setUserBirthdayTimestamp(strtotime($_POST['birthday']));
            $user->setLogin($_POST['login']);
            $user->setHashPassword(Application::$auth->getPasswordHash($_POST['password']));
            $this->userService->updateUser($user);
            $this->logger->info("Админ <{$_SESSION['login']}> изменил данные пользователя 
            (id: <{$_POST['id_user']}>, login: <{$_POST['login']}>, name: <{$_POST['name']}>, 
            lastname: <{$_POST['lastname']}>)");
            return $this->supportRender->printMessage("Данные пользователя обновлены",
                "Данные пользователя {$user->getUserName()} {$user->getUserLastname()} обновлены");
        } else {
            $this->logger->warning("Подозрительный запрос");
            header('Location: /404.php', true, 404);
            exit();
        }
    }

//    /** Удаление пользователя
//     * @throws Exception
//     * @deprecated
//     */
//    public function actionDelete(): string
//    {
//        $user = $this->findUser();
//        if ($this->userService->deleteFromStorage($user->getIdUser())) {
//            $this->logger->info("Админ <{$_SESSION['login']}> удалил пользователя
//            с id: <{$_GET['id_user']}>");
//            return json_encode(['status' => 'ok']);
//        } else {
//            $this->logger->error("Ошибка удаления пользователя из базы данных (id: <{$_GET['id_user']}>)");
//            throw new Exception("Ошибка удаления пользователя из базы данных");
//        }
//    }
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

        if ($this->userValidator->validateName($_GET['name']) &&
            $this->userValidator->validateName($_GET['lastname']) &&
            $this->userValidator->validateDate($_GET['birthday'])) {
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

        if ($this->requestValidator->checkQuery('name') &&
            $this->userValidator->validateName($_GET['name'])) {

            $user->setUserName($_GET['name']);
        }

        if ($this->requestValidator->checkQuery('lastname')) {
            $user->setUserLastname($_GET['lastname']);
        }

        if ($this->requestValidator->checkQuery('birthday')) {
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
        $id = $this->requestValidator->checkId();
        if ($id) {
            return $this->userService->findUserById($id);
        } else {
            $this->logger->error("Ошибка поиска пользователя в базе данных (id: <{$_GET['id_user']}>)");
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
                if ($this->requestValidator->validateRequestData($requestData)) {
                    throw new Exception("Попытка отправки тегов");
                }
            }
            $hash_password = $this->authService->getPasswordHash($password);
            $user = $this->userService->createUser($name, $lastname, $birthday, $login, $hash_password);
            return $this->supportRender->printMessage("Пользователь добавлен",
                "Пользователь {$user->getUserName()} {$user->getUserLastname()} добавлен");
        } catch (Exception $e) {
            $this->logger->error("Ошибка добавления пользователя в базу данных 
            (id: <{$_GET['id_user']}>, message: {$e->getMessage()})");
            return $this->supportRender->printMessage("Пользователь не добавлен", $e->getMessage());
        }
    }
//endregion region supportFunction

//region api
    /** Обновление списка пользователей
     * @return false|string
     */
    public function actionRefresh(): false|string
    {
        $first = null;

        if (isset($_POST['maxId']) && ($_POST['maxId'] > 0)) {
            $first = $_POST['maxId'];
        }

        $users = $this->userService->getAllUsersFromStorage($first);
        $usersData = [];

        if (count($users) > 0) {
            foreach ($users as $user) {
                $usersData[] = $user->getUserDataAsArray();
            }
        }

        return json_encode($usersData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /** Удаление пользователя
     * @return string
     * @throws Exception
     */
    public function actionDelete(): string
    {
        if (!$_SERVER['REQUEST_METHOD'] == 'DELETE') {
            throw new Exception("Неправильный тип запроса");
        }

        $content = file_get_contents("php://input");
        $id = str_replace("deletionUserId=", "", $content);

        if ($this->userService->deleteFromStorage($id)) {
            $this->logger->info("Админ <{$_SESSION['login']}> удалил пользователя с id: <$id>");
            return json_encode(['status' => 'ok']);
        } else {
            $this->logger->error("Ошибка удаления пользователя из базы данных (id: <$id>)");
//            throw new Exception("Ошибка удаления пользователя из базы данных");
            return json_encode(['status' => 'fail']);
        }
    }
//endregion api
}