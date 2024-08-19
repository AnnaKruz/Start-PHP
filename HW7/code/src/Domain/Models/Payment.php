<?php

namespace Geekbrains\Application1\Domain\Models;

use Geekbrains\Application1\Application\Application;
use \PDO;

class Payment
{
    private ?int $id_user_payment;
    private ?int $amount;
    private ?int $payment_timestamp;
    private ?int $id_user;

    public function getIdUserPayment(): int
    {
        return $this->id_user_payment;
    }

    public function setIdUserPayment(?int $id_user_payment): void
    {
        $this->id_user_payment = $id_user_payment;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(?int $amount): void
    {
        $this->amount = $amount;
    }

    public function getPaymentTimestamp(): int|null
    {
        return $this->payment_timestamp;
    }

    public function setPaymentTimestamp(?int $payment_timestamp): void
    {
        $this->payment_timestamp = $payment_timestamp;
    }

    public function getIdUser(): int
    {
        return $this->id_user;
    }

    public function setIdUser(?int $id_user): void
    {
        $this->id_user = $id_user;
    }

    public function __toString(): string
    {
        $date = date('d-m-y H:i', $this->getPaymentTimestamp());
        $amount = $this->getAmount() / 100;
        return "{$this->getIdUserPayment()} | $amount | $date | {$this->getIdUser()}";
    }


    public static function getAllPayment(): false|array
    {
        $sql = "SELECT * FROM user_payment";

        $connect = Application::$storage->get();
        $request = $connect->query($sql);
        $request->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Geekbrains\Application1\Domain\Models\Payment');
        $payments = $request->fetchAll();
//        $request->setFetchMode(PDO::FETCH_ASSOC);
//        $data = $request->fetchAll();
//        $payments = [];
//        foreach ($data as $item) {
//            $payment = new Payment();
//            $payment->setIdUserPayment($item['id_user_payment']);
//            $payment->setAmount($item['amount']);
//            $payment->setPaymentTimestamp($item['payment_timestamp']);
//            $payment->setIdUser($item['id_user']);
//            $payments[] = "$payment";
//        }
        return $payments;
    }

    public static function getById(int $id): false|array
    {
        $sql = 'select users.user_name, users.user_lastname, amount FROM users inner join user_payment on users.id_user = user_payment.user_id where users.id_user = :id';

        $connect = Application::$storage->get();
        $request = $connect->prepare($sql);
        $request->execute(["id" => $id]);
        $request->setFetchMode(PDO::FETCH_ASSOC);
        $data = $request->fetchAll();
        $payments = [];
        foreach ($data as $item) {
            $payment = "{$item['user_name']} {$item['user_lastname']} {$item['amount']}";
            $payments[] = "$payment";
        }
        return $payments;
    }
}