<?php

namespace Geekbrains\Application1\Domain\Render;

use Geekbrains\Application1\Application\Render;
use Geekbrains\Application1\Domain\Models\User;

class SupportRender implements ISupportRender
{
    private string $prefix = 'support/';
    private Render $render;

    public function __construct()
    {
        $this->render = new Render();
    }

    public function printMessage(string $title, string $message): string
    {
        return $this->render->renderPage(
            $this->prefix . 'message.twig',
            [
                'title' => $title,
                'message' => $message
            ]);
    }
}