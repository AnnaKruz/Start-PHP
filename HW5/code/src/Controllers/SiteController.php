<?php

namespace Geekbrains\Application1\Controllers;

use Geekbrains\Application1\Models\Info;
use Geekbrains\Application1\Models\Phone;
use Geekbrains\Application1\Render;

class SiteController
{
    /**
     * Информация о сайте
     * @return string
     */
    public function actionInfo(): string
    {
        $info = new Info();
        $render = new Render();

        return $render->renderPage('site-info.twig', [
            'title' => 'Информации о сервере',
            'name' => $info->getName(),
            'version' => $info->getVersion(),
            'agent' => $info->getAgent(),
        ]);
    }
}