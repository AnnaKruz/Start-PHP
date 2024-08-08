<?php

namespace Anna\HW4\task01;

/**
 * Клиент библиотеки
 */
class Holder extends Client
{
    protected string $phone;
    protected array $books;

    /**
     * @param string $name
     * @param string|null $phone
     */
    public function __construct(string $name, string $phone = null)
    {
        parent::__construct($name);
        $this->phone = $phone;
        $this->books = [];
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /** Взять книгу в библиотеке
     * @param Book $book
     * @return void
     */
    public function takeBook(Book $book): void
    {
        $this->books[$book->getId()] = $book->getTitle();
    }

    /** Вернуть книгу в библиотеку
     * @param int $bookId
     * @return void
     */
    public function returnBook(int $bookId): void
    {
        unset($this->books[$bookId]);
    }
}