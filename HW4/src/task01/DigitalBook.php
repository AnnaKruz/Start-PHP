<?php

namespace Anna\HW4\task01;

class DigitalBook extends AbstractBook
{
    protected float $price;
    private static int $idCounter = 0;

    /**
     * @param float $price
     */
    public function __construct(string $title, string $author, int $age, int $pageCount, float $price)
    {
        parent::__construct($title, $author, $age, $pageCount);
        $this->id = ++self::$idCounter;
        $this->price = $price;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

}