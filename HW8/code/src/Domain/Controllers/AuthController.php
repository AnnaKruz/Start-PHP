<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Exception;
use Geekbrains\Application1\Application\Application;
use Geekbrains\Application1\Application\FileLogger;
use Geekbrains\Application1\Application\Validator;
use Geekbrains\Application1\Domain\Models\User;
use Geekbrains\Application1\Domain\Render\AuthRender;
use Geekbrains\Application1\Domain\Render\IAuthRender;
use Geekbrains\Application1\Domain\Render\ISupportRender;
use Geekbrains\Application1\Domain\Render\SupportRender;
use JetBrains\PhpStorm\NoReturn;
use Monolog\Logger;

class AuthController extends Controller
{
    private IAuthRender $authRender;
    private Logger $logger;

    protected array $actionsPermissions = [
        'actionAuthentication' => ['user'],
        'actionLogin' => ['user'],
        'actionLogout' => ['user'],
        'actionRegistration' => ['user'],
        'actionCreation' => ['user'],
    ];

    public function __construct()
    {
        parent::__construct();
        $this->authRender = new AuthRender();
        $this->logger = FileLogger::createLogger("auth_logger", "auth-log", "auth");
    }

    /** Аутентификация
     * @throws Exception
     */
    #[NoReturn] public function actionAuthentication(): void
    {
        $user = $this->userService->authUser($_POST['login'], $_POST['password']);
        $this->authService->setParams($user);

        if (isset($_POST['remember']) && $_POST['remember'] == 'on') {
            $this->authService->giveToken($user);
        }

        $this->logger->info("Пользователь <{$_POST['login']}> авторизировался на сайте");

        header('Location: /', true, 303);
        exit();
    }

    /** Форма авторизации
     * @return string
     */
    public function actionLogin(): string
    {
        return $this->authRender->renderAuthForm("auth", "Вход", "Введите логин и пароль",
            "/auth/authentication", !empty($_SESSION['login']) ? $_SESSION['login'] : "");
    }

    /** Форма регистрации
     * @return string
     */
    public function actionRegistration(): string
    {
        if(!empty($_SESSION) && isset($_SESSION['registration_error'])) {
            return $this->authRender->renderAuthForm("reg", "Вход", "Введите логин и пароль",
                "/auth/creation", $_SESSION['user_data']['login'], $_SESSION['user_data']['name'],
                $_SESSION['user_data']['lastname'], $_SESSION['user_data']['birthday'],
                $_SESSION['registration_error']);
        } else {
            return $this->authRender->renderAuthForm("reg", "Вход", "Введите логин и пароль", "/auth/creation");
        }
    }

    /** Создание нового пользователя
     * @return string
     * @throws Exception
     */
    #[NoReturn] public function actionCreation(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && Validator::checkCreateUserCountParams()) {
            $validateErrors = Validator::checkUserData($_POST['login'], $_POST['password'],
                $_POST['name'], $_POST['lastname'], $_POST['birthday']);
            if (count($validateErrors)) {
                $errorMessage = implode("\n", $validateErrors);
                $_SESSION['registration_error'] = $errorMessage;
                $_SESSION['user_data'] = ["name" => $_POST['name'], "lastname" => $_POST['lastname'],
                    "birthday" => $_POST['birthday'], "login" => $_POST['login']];
                header('Location: /auth/registration');
                exit();
//                return $this->supportRender->printMessage("Некорректный ввод", $errorMessage);
            }

            $hash_password = $this->authService->getPasswordHash($_POST['password']);
            $user = $this->userService->createUser($_POST['name'], $_POST['lastname'],
                $_POST['birthday'], $_POST['login'], $hash_password);

            $this->authService->setParams($user);

            $this->logger->info("Создан новый пользователь с ником <{$_POST['login']}>");

            header('Location: /', true, 303);
        } else {
            $this->logger->warning("Подозрительный запрос");
            header('Location: /404.php', true, 404);
        }
        exit();
    }

    /** Обработка выхода из учётной записи пользователя
     * @throws Exception
     */
    #[NoReturn] public function actionLogout(): void
    {
        $id = $_SESSION['id_user'];
        $this->authService->logout($id);

        $this->logger->info("Пользователь <{$_SESSION['login']}> разлогинился");

        header('Location: /', true, 303);
        exit();
    }
}