<?php

namespace Geekbrains\Application1\Application;

use Exception;
use Geekbrains\Application1\Domain\Controllers\AbstractController;
use Geekbrains\Application1\Domain\Controllers\Controller;
use Geekbrains\Application1\Infrastructure\Config;
use Geekbrains\Application1\Infrastructure\Storage;

final class Application
{

    private string $appNamespace;
    public static Config $config;
    public static Storage $storage;
    public static Auth $auth;

    public function __construct()
    {
        Application::$config = new Config();
        $this->appNamespace = self::$config->get()['namespace']['app'];
        Application::$storage = new Storage();
        Application::$auth = new Auth();
    }

    /**
     * @throws Exception
     */
    public function run(): string
    {
        session_start();

        $routeArray = explode('/', $_SERVER['REQUEST_URI']);

        if (isset($routeArray[1]) && $routeArray[1] != '') {
            $controllerName = $routeArray[1];
        } else {
            $controllerName = "page";
        }

        $controllerName1 = $this->appNamespace . ucfirst($controllerName) . "Controller";

        if (class_exists($controllerName1)) {
            // пытаемся вызвать метод
            if (isset($routeArray[2]) && $routeArray[2] != '') {
                $methodName = $routeArray[2];
            } else {
                $methodName = "index";
            }

            $methodName1 = "action" . ucfirst($methodName);

            if (method_exists($controllerName1, $methodName1)) {
                $controllerInstance = new $controllerName1();

                if ($this->checkAccessToMethod($controllerInstance, $methodName1)) {
                    return call_user_func_array(
                        [$controllerInstance, $methodName1],
                        []
                    );
                } else {
                    throw new Exception("В доступе отказано");
                }
            } else {
                Render::notFound();
                die();
            }
        } else {
            Render::notFound();
            die();
        }
    }

    /** Проверка доступности метода
     * @param Controller $controllerInstance
     * @param string $methodName
     * @return bool
     */
    private function checkAccessToMethod(Controller $controllerInstance, string $methodName): bool {
        $userRoles = $controllerInstance->getUserRoles();
        $rules = $controllerInstance->getActionsPermissions($methodName);

        if(!empty($rules)){
            foreach($rules as $rolePermission){
                if(in_array($rolePermission, $userRoles)){
                    return true;
                }
            }
        }

        return false;
    }
}