<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Geekbrains\Application1\Application\Render;
use Geekbrains\Application1\Domain\Models\Phone;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AboutController extends Controller
{
    protected array $actionsPermissions = [
        "actionIndex" => ['user']
    ];

    /** Отрисовка страницы "О нас"
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function actionIndex(): string
    {
        $phone = (new Phone())->getPhone();
        $render = new Render();

        return $render->renderPage('about/about.twig', [
            'phone' => $phone
        ]);
    }
}