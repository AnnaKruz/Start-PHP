<?php

namespace Geekbrains\Application1\Application;

use Exception;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class Render
{

    private string $viewFolder;
    private FilesystemLoader $loader;
    private Environment $environment;


    public function __construct()
    {
        $this->viewFolder = Application::$config->get()['path']['view'];
        $this->loader = new FilesystemLoader(dirname($_SERVER['DOCUMENT_ROOT']) . $this->viewFolder);
        $this->environment = new Environment($this->loader, [
            // 'cache' => $_SERVER['DOCUMENT_ROOT'].'/cache/',
        ]);
    }

    /** Отрисовка страницы
     * @param string $contentTemplateName
     * @param array $templateVariables
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function renderPage(string $contentTemplateName = 'page-index.twig', array $templateVariables = []): string
    {
        $template = $this->environment->load('block/main.twig');

        $style = Application::$config->get()['public']['style'] . "style.css";
        $templateVariables['style'] = $style;

        $templateVariables['header'] = 'block/header.twig';
        $templateVariables['sidebar'] = 'block/sidebar.twig';
        $templateVariables['footer'] = 'block/footer.twig';

        $templateVariables['content_template_name'] = $contentTemplateName;
        $templateVariables['title'] = $templateVariables['title'] ?? 'Имя страницы';

        $templateVariables['time'] = time();

        if (!empty($_SESSION['id_user'])) {
            $templateVariables['auth'] = true;
            $templateVariables['login'] = $_SESSION['login'];
        }

        return $template->render($templateVariables);
    }

    /** Обработка ошибки 404
     * @return void
     */
    public static function notFound(): void
    {
        // через nginx
        http_response_code(404);
        // через php
//        header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found", true, 404);
//        $page404 = $_SERVER['DOCUMENT_ROOT'] . Application::config()['public']['html'] . "404.html";
//        include($page404);
    }

    /** Информация об ошибках
     * @param Exception $e
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function renderExceptionPage(Exception $e): string
    {
        $render = new Render();

        return $render->renderPage('/support/error.twig', [
            "error" => $e->getMessage(),
//            "trace" => $e->getTraceAsString(),
        ]);
    }
}
