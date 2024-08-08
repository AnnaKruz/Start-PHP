<?php

namespace Anna\HW4\task01;

interface IBookTrader
{
    function trade(DigitalBook $book, WebClient $client);
}