<?php

namespace Geekbrains\Application1\Domain\Render;

interface IAuthRender
{
    function renderAuthForm(string $templateName, string $title, string $subtitle, string $action,
                            string $login = ""): string;
}