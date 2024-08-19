<?php

namespace Geekbrains\Application1\Domain\Render;

interface ISupportRender
{
    function printMessage(string $title, string $message): string;
}