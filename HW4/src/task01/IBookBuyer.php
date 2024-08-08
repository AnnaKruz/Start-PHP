<?php

namespace Anna\HW4\task01;

interface IBookBuyer
{
    function bay(DigitalBook $book, WebShop $shop);
}