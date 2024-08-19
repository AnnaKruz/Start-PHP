<?php

namespace Geekbrains\Application1\Domain\Render;

interface IUserRender
{
    function renderAddForm(string $title, string $subtitle, string $action, string $name = "",
                           string $lastname = "", string|int $birthday = ""): string;
    function renderUsersList(string $mode, array $users = []): string;
}