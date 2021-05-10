<?php

namespace src\Model;

class Router
{
    private static $routes = [];

    public static function add($pattern, $callback)
    {
        $pattern = '/^' . str_replace('/', '\/', $pattern) . '$/';
        self::$routes[$pattern] = $callback;
    }

    public static function execute($url)
    {
        $url = explode('?', $url)[0];
        foreach (self::$routes as $pattern => $callback)
        {
            if (preg_match($pattern, $url, $params)) {
                array_shift($params);
                return call_user_func_array($callback, $params);
            }
        }

        http_response_code(404);
        return include PAGE_404;
    }
}
