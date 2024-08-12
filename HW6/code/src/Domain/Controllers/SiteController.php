<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Geekbrains\Application1\Application\Render;
use Geekbrains\Application1\Domain\Models\Info;

class SiteController
{

    private string $prefix = 'site/';

    /**
     * Информация о сайте
     * @return string
     */
    public function actionInfo(): string
    {
        $info = new Info();
        $render = new Render();

        return $render->renderPage($this->prefix.'site-info.twig', [
            'title' => 'Информации о сервере',
            'name' => $info->getName(),
            'version' => $info->getVersion(),
            'agent' => $info->getAgent(),
        ]);
    }
}