<?php

namespace App;

class Router
{
    protected $routes = [];

    private function addRoute($route, $controller, $action, $method)
    {
        $this->routes[$method][$route] = [
            'controller' => $controller,
            'action' => $action,
            'pattern' => $this->convertRouteToPattern($route)
        ];
    }

    private function convertRouteToPattern($route)
    {
        // Convert route with dynamic segments to a regex pattern
        return '#^' . preg_replace('/{[^}]+}/', '([^/]+)', $route) . '$#';
    }

    public function get($route, $controller, $action)
    {
        $this->addRoute($route, $controller, $action, "GET");
    }

    public function post($route, $controller, $action)
    {
        $this->addRoute($route, $controller, $action, "POST");
    }

    public function dispatch()
    {
        $uri = strtok($_SERVER['REQUEST_URI'], '?');
        $method = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $route => $info) {
                if (preg_match($info['pattern'], $uri, $matches)) {
                    // Remove the first match (the full match)
                    array_shift($matches);

                    $controller = $info['controller'];
                    $action = $info['action'];

                    $controller = new $controller();
                    call_user_func_array([$controller, $action], $matches);
                    return;
                }
            }
        }

        throw new \Exception("No route found for URI: $uri");
    }
}