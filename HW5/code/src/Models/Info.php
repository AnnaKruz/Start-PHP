<?php

namespace Geekbrains\Application1\Models;

/**
 * Служебная информация
 */
class Info
{
    private string $name;
    private string $version;
    private string $agent;

    /**
     *
     */
    public function __construct()
    {
        $this->name = $_SERVER['SERVER_NAME'];
        $this->version = phpversion();
        $this->agent = $_SERVER['HTTP_USER_AGENT'];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getAgent(): string
    {
        return $this->agent;
    }


}