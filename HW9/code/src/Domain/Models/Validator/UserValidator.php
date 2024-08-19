<?php

namespace Geekbrains\Application1\Domain\Models\Validator;

/**
 * Валидатор пользовательских данных
 */
class UserValidator extends Validator
{
    /**
     * Валидация даты
     * @param string $date
     * @return bool
     */
    public function validateDate(string $date): bool
    {
        $dateBlocks = explode("-", $date);

        if (count($dateBlocks) < 3) {
            return false;
        }

        if (isset($dateBlocks[0]) && $dateBlocks[0] > 31 || $dateBlocks[0] < 1) {
            return false;
        }

        if (isset($dateBlocks[1]) && $dateBlocks[1] > 12 || $dateBlocks[1] < 1) {
            return false;
        }

        if (isset($dateBlocks[2]) && $dateBlocks[2] > date('Y') && $dateBlocks[2] < 1900) {
            return false;
        }

        return true;
    }

    /**
     * Валидация по имени
     * @param string $name
     * @return bool
     */
    public function validateName(string $name): bool
    {
        if (strlen($name) === 0) return false;
        $arr = [];
        for ($i = 0; $i < strlen($name); $i++) {
            $arr[] = $name[$i];
        }
        if (array_intersect([0, 1, 2, 3, 4, 5, 6, 7, 8, 9, '=', '+', '/', '*', '~', '?', '|', '\\', '<', '>', '{', '}', '[', ']', ':', ';', '!'], $arr)) return false;

        return true;
    }

    /** Валидация логина
     * @param string $login
     * @return bool
     */
    public function validateLogin(string $login): bool
    {
        $pattern = '/^(?=.*\S)(?!<.*>).{3,20}$/';
        return preg_match($pattern, $login) && !preg_match('/\s/', $login);
    }

    /** Проверка надёжности пароля
     * @param string $password
     * @return bool
     */
    public function validatePassword(string $password): bool
    {
        $pattern = '/^(?=.*\d)(?=.*\w)(?=.*\S)(?!\s).{8,16}$/';
        return mb_strtolower($password) !== 'pass' &&
            mb_strtolower($password) !== 'password' &&
            preg_match($pattern, $password);
    }
}