<?php

namespace Anna\HW4\task01;

/**
 * Библиотека
 */
class Library implements IBookManager
{
    private string $name;
    private string $address;
    private array $closets;
    private array $holders;
    private array $books;

    /**
     * @param string $name
     * @param string $address
     */
    public function __construct(string $name, string $address)
    {
        $this->name = $name;
        $this->address = $address;
        $this->closets = [];
        $this->holders = [];
        $this->books = [];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /** Создать шкаф
     * @param int $shelfCount
     * @return void
     */
    public function createCloset(int $shelfCount): void
    {
        $newCloset = new Closet($shelfCount);
        $this->closets[$newCloset->getId()] = $newCloset;
    }

    /** Добавить шкаф
     * @param Closet $closet
     * @return void
     */
    public function addCloset(Closet $closet) : void
    {
        $this->closets[$closet->getId()] = $closet;
    }

    /** Удалить шкаф 
     * @param Closet $closet
     * @return void
     */
    public function deleteCloset(Closet $closet): void
    {
        array_splice($this->closets, array_search($closet, $this->closets), 1);
        //TODO: Добавить логику перемещения
    }

    /** Добавить любителя ходить в библиотеку
     * @param Holder $holder
     * @return void
     */
    public function addHolder(Holder $holder): void
    {
        $this->holders[$holder->getName()] = $holder;
    }

    /** Увы, но люди умирают
     * @param string $name
     * @return void
     */
    public function deleteHolder(string $name): void
    {
        unset($this->holders[$name]);
    }

    /** Добавить книгу в библиотеку
     * @param Book $book
     * @param int $closetId
     * @param int $shelfRow
     * @return void
     */
    public function addBook(Book $book, int $closetId, int $shelfRow): void
    {
        //Заносим данные о хранении в экземпляр книги
        $book->setClosetId($closetId);
        $book->setShelfId($shelfRow);
        //Добавляем книгу в список книг библиотеки и на полку шкафа
        $this->books[$book->getId()] = $book;
        $this->closets[$closetId]->getShelf($shelfRow)->addBook($book);
    }

    /** Ничто не вечно
     * @param int $bookId
     * @return void
     */
    public function deleteBook(int $bookId): void
    {
        unset($this->books[$bookId]);
    }

    /** Выдать книгу читателю
     * @param Book $book
     * @param Holder $holder
     * @return void
     */
    public function giveBook(Book $book, Holder $holder): void
    {
        //Получаем id полки и шкафа
        $shelfId = $book->getShelfId();
        $closetId = $book->getClosetId();
        //Удаляем информацию о хранении
        $this->closets[$closetId]->getShelf($shelfId)->takeBook($book->getId());
        //Выдаём книгу читателю
        $book->giveBook($holder);
    }

    /** Вернуть долг
     * @param Book $book
     * @param Holder $holder
     * @return void
     */
    public function takeBook(Book $book, Holder $holder): void
    {
        $book->comeBack();
        $holder->returnBook($book->getTitle());
        //TODO: Логика размещения книги на полке
    }

    /** Получить информацию о шкафе
     * @param int $id
     * @return Closet
     */
    public function getCloset(int $id) : Closet
    {
        return $this->closets[$id];
    }
}