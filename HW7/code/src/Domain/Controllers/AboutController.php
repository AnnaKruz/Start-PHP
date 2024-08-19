<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Geekbrains\Application1\Application\Render;
use Geekbrains\Application1\Domain\Models\Phone;

class AboutController extends Controller
{
    protected array $actionsPermissions = [
        "actionIndex" => ['user']
    ];

    public function actionIndex(): string
    {
        $phone = (new Phone())->getPhone();
        $render = new Render();

        return $render->renderPage('about/about.twig', [
            'phone' => $phone
        ]);
    }
}