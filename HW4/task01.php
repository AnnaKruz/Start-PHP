<?php

require './vendor/autoload.php';

use Anna\HW4\task01\Library;
use Anna\HW4\task01\Book;
use Anna\HW4\task01\Closet;
use Anna\HW4\task01\Holder;
use Anna\HW4\task01\WebClient;
use Anna\HW4\task01\WebShop;
use Anna\HW4\task01\DigitalBook;

//Создаём библиотеку
$lib = new Library("Библиотека №1", "г.Екатеринбург, ул.Малышева, д.1");
$closet = new Closet(8);
$lib->addCloset($closet);

//Создаём книгу
$book = new Book("Кот Бизилио", "Кукрыникс Островский", 2021, 1500);
$lib->addBook($book, $closet->getId(), 5);

//Создаём читателя
$holder = new Holder("Вася", "+7(901)150-45-02");
$lib->addHolder($holder);

print_r($lib);

//Выдаём книгу читателю
$lib->giveBook($book, $holder);

echo PHP_EOL . "--- После получения книги в библиотеке ---" . PHP_EOL;
print_r($lib);

echo PHP_EOL . "--- Создание интернет-магазина ---" . PHP_EOL;
//Создаём интернет-магазин
$shop = new WebShop("Biblioteka", "http://books.ru", "123456789101112");

//Создаём электронную книгу
$digitBook = new DigitalBook("Отцы и дети", "Лев Толстой", 1867, 1300, 1300);
$shop->addBook($digitBook);

//Создаём клиента интернет-магазина
$client = new WebClient("Петр", "petia@mail.ru", "0000 1111 2222 3333");

print_r($shop);

echo PHP_EOL . "--- После продажи книги ---" . PHP_EOL;
$shop->trade($digitBook, $client);
print_r($shop);
print_r($client);