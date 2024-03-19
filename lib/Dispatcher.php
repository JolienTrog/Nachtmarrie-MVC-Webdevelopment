<?php

namespace Nachtmerrie;

class Dispatcher
{
    private const BASE_URL = '/';

    public static function dispatch()
    {
        $requestedUrlArray = explode('?', $_SERVER['REQUEST_URI']);
        $requestedUrl = $requestedUrlArray[0];

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