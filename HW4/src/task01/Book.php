<?php

namespace Anna\HW4\task01;

/**
 * Книга, хранимая в библиотеке
 */
class Book extends AbstractBook
{
    /**
     * @var Holder|null Клиент, взявший книгу в библиотеке
     */
    private Holder|null $holder;
    protected int|null $shelfId;
    protected int|null $closetId;
    private static int $idCounter = 0;

    public function __construct(string $title, string $author, int $age, int $pageCount)
    {
        parent::__construct($title, $author, $age, $pageCount);
        $this->id = ++self::$idCounter;
    }

    /** Выдать книгу
     * @param Holder $holder
     * @return void
     */
    public function giveBook(Holder $holder): void
    {
        $this->holder = $holder;
        $holder->takeBook($this);
        $this->shelfId = null;
        $this->closetId = null;
    }

    /** Вернуть книгу
     * @return void
     */
    public function comeBack(): void
    {
        $this->holder->returnBook($this->title);
        $this->holder = null;
        //TODO: добавить логику размещения на полках
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getShelfId(): int
    {
        return $this->shelfId;
    }

    public function setShelfId(int $shelfId): void
    {
        $this->shelfId = $shelfId;
    }

    public function getClosetId(): int
    {
        return $this->closetId;
    }

    public function setClosetId(int $closetId): void
    {
        $this->closetId = $closetId;
    }


}