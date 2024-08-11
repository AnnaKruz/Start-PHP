<?php

namespace Geekbrains\Application1;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class Render {

    private string $viewFolder = '/src/Views/';
    private FilesystemLoader $loader;
    private Environment $environment;


    public function __construct(){

        $this->loader = new FilesystemLoader(dirname(__DIR__) . $this->viewFolder);
        $this->environment = new Environment($this->loader, [
           // 'cache' => $_SERVER['DOCUMENT_ROOT'].'/cache/',
        ]);
    }

    public function renderPage(string $contentTemplateName = 'page-index.twig', array $templateVariables = []) {
        $template = $this->environment->load('main.twig');

        $style = Application::config()['public']['style'] . "style.css";
        $templateVariables['style'] = $style;

        $templateVariables['header'] = 'header.twig';
        $templateVariables['sidebar'] = 'sidebar.twig';
        $templateVariables['footer'] = 'footer.twig';

        $templateVariables['content_template_name'] = $contentTemplateName;
        $templateVariables['title'] = $templateVariables['title'] ?? 'Имя страницы';

        $templateVariables['time'] = time();

        return $template->render($templateVariables);
    }

    public static function renderError(): void
    {
        // через nginx
        http_response_code(404);
        // через php
//        header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found", true, 404);
//        $page404 = $_SERVER['DOCUMENT_ROOT'] . Application::config()['public']['html'] . "404.html";
//        include($page404);
    }
}
