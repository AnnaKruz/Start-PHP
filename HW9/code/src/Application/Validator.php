<?php

namespace Geekbrains\Application1\Application;

use Exception;
use Geekbrains\Application1\Domain\Models\User;

class Validator
{
    /** Проверка корректности введённого в url идентификатора
     * @return int
     */
    public static function checkId(): int
    {
        return key_exists('id', $_GET) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;
    }

    /** Проверка существования ключа запроса
     * @param string $key
     * @return bool
     */
    public static function checkQuery(string $key): bool
    {
        return array_key_exists($key, $_GET);
    }

    /** Проверка входящих данных на наличие скриптов
     * @param string $requestData
     * @return bool
     */
    public static function validateRequestData(string $requestData): bool
    {
        return preg_match('/<.*>/', $requestData);
    }

    /** Проверка параметров POST-запроса на создание нового пользователя
     * @return bool
     */
    public static function checkCreateUserCountParams(): bool
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
    public static function checkConfirmPassword() : bool
    {
        return $_POST['password'] == $_POST['confirm'];
    }

    /** Проверка пользовательского ввода на наличие некорректности переданных значений
     * @param string $login
     * @param string $password
     * @param string $name
     * @param string $lastname
     * @return array
     */
    public static function checkUserData(string $login, string $password, string $name,
                                         string $lastname, string $birthday): array
    {
        $errors = [];
        if (!Validator::checkConfirmPassword()) {
            $errors[] = "Поля 'Пароль' и 'Подтверждение' не совпадают";
        } else if (!User::validatePassword($_POST['password'])) {
            $errors[] = "Слишком простой пароль (необходимо ввести минимум 8 символов, 
            используя нижний и верхний регистры, числа и символы";
        }
        if (!User::validateLogin($_POST['login'])) {
            $errors[] = "Введён некорректный логин";
        }
        if (!User::validateName($_POST['name'])) {
            $errors[] = "Имя введено некорректно";
        }
        if (!User::validateName($_POST['lastname'])) {
            $errors[] = "Фамилия введена некорректно";
        }
        if (strtotime($birthday) > time()){
            $errors[] = "Дата рождения введена некорректно";
        }

        return $errors;
    }
}