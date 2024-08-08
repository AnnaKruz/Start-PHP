<?php

namespace Anna\HW4\task01;

/**
 * Книжный шкаф
 */
class Closet
{
    private int $id;
    private array $shelfs;
    private static int $idCounter = 0;

    /**
     * @param int $shelfCount
     */
    public function __construct(int $shelfCount)
    {
        $this->id = ++self::$idCounter;
        $this->shelfs = [];
        for ($i = 0; $i < $shelfCount; $i++) {
            $this->shelfs[$i+1] = new Shelf();
        }
    }

    /** Получить информацию о полке по номеру
     * @param int $row
     * @return Shelf
     */
    public function getShelf(int $row) : Shelf
    {
        return $this->shelfs[$row];
    }

    public function getId(): int
    {
        return $this->id;
    }


}