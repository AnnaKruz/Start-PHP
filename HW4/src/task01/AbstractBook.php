<?php

namespace Anna\HW4\task01;

/**
 * Абстрактная книга 
 */
abstract class AbstractBook
{
    protected int $id;
    protected string $title;
    protected string $author;
    protected int $age;
    protected int $pageCount;

    /**
     * @param string $author
     * @param int $age
     * @param int $pageCount
     */
    public function __construct(string $title, string $author, int $age, int $pageCount)
    {
        $this->title = $title;
        $this->author = $author;
        $this->age = $age;
        $this->pageCount = $pageCount;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function setAge(int $age): void
    {
        $this->age = $age;
    }

    public function getPageCount(): int
    {
        return $this->pageCount;
    }

    public function setPageCount(int $pageCount): void
    {
        $this->pageCount = $pageCount;
    }

    public function getId(): int
    {
        return $this->id;
    }


}