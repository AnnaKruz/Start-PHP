<?php

namespace Anna\HW4\task01;

/**
 * Книжная полка в библиотеке
 */
class Shelf
{
    private array $books;

    public function __construct()
    {
        $this->books = [];
    }

    /** Добавить книгу на полку
     * @param Book $book
     * @return void
     */
    public function addBook(Book $book): void
    {
        $this->books[$book->getId()] = $book;
    }

    /** Взять книгу с полки
     * @param int $id
     * @return void
     */
    public function takeBook(int $id): void
    {
        unset($this->books[$id]);
    }
}