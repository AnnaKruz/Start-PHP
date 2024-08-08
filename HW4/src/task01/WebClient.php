<?php

namespace Anna\HW4\task01;

/**
 * Название для покупателя электронных книг
 */
class WebClient extends Client implements IBookBuyer
{
    protected int $id;
    protected string $email;
    protected string $creditCartNumber;
    protected array $digitalBooks;
    private static int $idCounter = 0;

    /**
     * @param string $name
     * @param string $email
     */
    public function __construct(string $name, string $email, string $creditCartNumber)
    {
        parent::__construct($name);
        $this->id = ++self::$idCounter;
        $this->email = $email;
        $this->creditCartNumber = $creditCartNumber;
        $this->digitalBooks = [];
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getCreditCartNumber(): string
    {
        return $this->creditCartNumber;
    }

    public function setCreditCartNumber(string $creditCartNumber): void
    {
        $this->creditCartNumber = $creditCartNumber;
    }

    public function getId(): int
    {
        return $this->id;
    }

    /** Покупка  книги
     * @param DigitalBook $book
     * @param WebShop $shop
     * @return void
     */
    function bay(DigitalBook $book, WebShop $shop): void
    {
        //TODO оплата книги (списание средств / подтверждение оплаты и т.д.)
        $this->digitalBooks[$book->getId()] = $book->getTitle();
    }
}