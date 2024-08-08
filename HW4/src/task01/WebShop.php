<?php

namespace Anna\HW4\task01;

/**
 * Интернет-магазин
 */
class WebShop implements IBookManager, IBookTrader
{
    protected string $name;
    protected string $url;
    protected string $bankNumber;
    protected array $books;
    protected array $clients;

    /**
     * @param string $name
     * @param string $url
     * @param string $bankNumber
     */
    public function __construct(string $name, string $url, string $bankNumber)
    {
        $this->name = $name;
        $this->url = $url;
        $this->bankNumber = $bankNumber;
        $this->books = [];
        $this->clients = [];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getBankNumber(): string
    {
        return $this->bankNumber;
    }

    public function setBankNumber(string $bankNumber): void
    {
        $this->bankNumber = $bankNumber;
    }

    /** Добавить новую книгу 
     * @param DigitalBook $book
     * @return void
     */
    public function addBook(DigitalBook $book): void
    {
        $this->books[$book->getId()] = $book;
    }

    /** Достать книгу
     * @param int $bookId
     * @return void
     */
    public function deleteBook(int $bookId): void
    {
        unset($this->books[$bookId]);
    }

    function trade(DigitalBook $book, WebClient $client): void
    {
        $client->bay($book, $this);
        $this->clients[$client->getId()] = $client->getName();
        // TODO: банкинг, логирование и т.д.
    }
}