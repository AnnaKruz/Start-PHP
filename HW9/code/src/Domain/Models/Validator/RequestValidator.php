<?php

namespace Geekbrains\Application1\Domain\Models\Validator;

/**
 * Валидатор запросов пользователя
 */
class RequestValidator extends Validator
{
    private UserValidator $userValidator;

    public function __construct()
    {
        $this->userValidator = new UserValidator();
    }


    /** Проверка корректности введённого в url идентификатора
     * @return int
     */
    public function checkId(): int
    {
        return key_exists('id', $_GET) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;
    }

    /** Проверка существования ключа запроса
     * @param string $key
     * @return bool
     */
    public function checkQuery(string $key): bool
    {
        return array_key_exists($key, $_GET);
    }

    /** Проверка входящих данных на наличие скриптов
     * @param string $requestData
     * @return bool
     */
    public function validateRequestData(string $requestData): bool
    {
        return preg_match('/<.*>/', $requestData);
    }

    /** Проверка параметров POST-запроса на создание нового пользователя
     * @return bool
     */
    public function checkCreateUserCountParams(): bool
    {
        return isset($_POST['name']) &&
            isset($_POST['lastname']) &&
            isset($_POST['birthday']) &&
            isset($_POST['login']) &&
            isset($_POST['password']) &&
            isset($_POST['confirm']);
    }

    /** Проверка совпадения пароля и подтверждения
     * @return bool
     */
    public function checkConfirmPassword() : bool
    {
        return $_POST['password'] == $_POST['confirm'];
    }

    /** Проверка пользовательского ввода на наличие некорректности переданных значений
     * @return array
     */
    public function checkUserData(): array
    {
        $errors = [];
        if (!$this->checkConfirmPassword()) {
            $errors[] = "Поля 'Пароль' и 'Подтверждение' не совпадают";
        } else if (!$this->userValidator->validatePassword($_POST['password'])) {
            $errors[] = "Слишком простой пароль (необходимо ввести минимум 8 символов, 
            используя нижний и верхний регистры, числа и символы";
        }
        if (!$this->userValidator->validateLogin($_POST['login'])) {
            $errors[] = "Введён некорректный логин";
        }
        if (!$this->userValidator->validateName($_POST['name'])) {
            $errors[] = "Имя введено некорректно";
        }
        if (!$this->userValidator->validateName($_POST['lastname'])) {
            $errors[] = "Фамилия введена некорректно";
        }
        if (strtotime($_POST['birthday']) > time()){
            $errors[] = "Дата рождения введена некорректно";
        }

        return $errors;
    }
}