<?php

namespace Nachtmerrie;

class Dispatcher
{
    /**
     * @var string base url
     */
    private const BASE_URL = '/';

    /**
     * @return void calls the controller and corresponding method
     * @return void
     */
    public static function dispatch() : void
    {
        $requestedUrl = explode('?', $_SERVER['REQUEST_URI'])[0];

        $parts = explode(
            '/',
            trim(
                substr($requestedUrl, strlen(self::BASE_URL)),
                '/'
            )
        );

        $controllerName = $parts[0] === '' ? 'index' : $parts[0];
        $actionName = $parts[1] ?? 'index';

        $controllerClassPath = "\\Nachtmerrie\\Controller\\" . ucfirst(strtolower($controllerName)) . 'Controller';
        $actionMethod = strtolower($actionName) . 'Action';

        if (!class_exists($controllerClassPath) || !method_exists($controllerClassPath, $actionMethod)) {
            http_response_code(404);
            return;
        }
        (new $controllerClassPath())->$actionMethod();
    }
}