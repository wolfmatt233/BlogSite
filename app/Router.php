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
        // Regex to look for dynamic {id} to be matched on dispatch
        // for route '/posts/{id}' if uri is '/posts/15', match {id} with 15
        return '#^' . preg_replace('/{[^}]+}/', '([0-9]+)', $route) . '$#';
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
        $uri = strtok($_SERVER['REQUEST_URI'], token: '?'); // i.e., '/users/login'
        $method = $_SERVER['REQUEST_METHOD']; // GET or POST

        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $route => $info) {
                if (preg_match($info['pattern'], $uri, $matches)) {
                    array_shift($matches);

                    $controller = $info['controller'];
                    $action = $info['action'];

                    $controller = new $controller();

                    //calls controller and method, passes over id as $matches if necessary
                    call_user_func_array([$controller, $action], $matches);
                    return;
                }
            }
        }

        throw new \Exception("No route found for URI: $uri");
    }
}