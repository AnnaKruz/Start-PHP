<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Geekbrains\Application1\Application\Render;
use Geekbrains\Application1\Domain\Models\Payment;
use Geekbrains\Application1\Domain\Models\User;

class PaymentController
{
    private string $prefix = 'payment/';

    public function actionIndex(): string
    {
        $render = new Render();

        $payments = Payment::getAllPayment();

        return $render->renderPage($this->prefix . 'payment-index.twig', [
            'title' => 'Страница платежей',
            'payments' => $payments
        ]);
    }

    /**
     * @throws \Exception
     */
    public function actionFind(): string
    {
        $render = new Render();
        $id = key_exists('id', $_GET) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;

        if (!User::isExist($id)) throw new \Exception("Пользователь не существует");
        $payments = Payment::getById($id);
        return $render->renderPage($this->prefix . 'payment-find.twig', [
            'title' => 'Страница платежей выбранного юзера',
            'payments' => $payments,
            'id' => $id
        ]);
    }
}