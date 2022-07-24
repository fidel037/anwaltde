<?php

namespace App\Router;

use App\Controller\HomeController;
use \Exception;
use Symfony\Component\HttpFoundation\Request;

class Router {

    private $routes = [
        [
            'path' => '/',
            'controller' => HomeController::class,
            'action' => 'index',
            'method' => 'GET'
        ]
    ];

    private function validateRoutes()
    {
        foreach ($this->routes as $route) {
            if (!method_exists($route['controller'], $route['action'])) {
                throw new Exception(
                    sprintf(
                        'Route method does not exist. Route: %s Controller: %s Method: %s',
                        $route['path'],
                        $route['controller'],
                        $route['method']
                    ),
                    500
                );
            }
        }
    }

    public function handle()
    {
        $this->validateRoutes();
        $request = $this->getRequest();
        [$controller, $action, $method]  = $this->getRoute($request);

        $this->validateRequest($request, $method);

        return $this->next($controller, $action, $request);
    }

    private function validateRequest(Request $request, $method)
    {
        if ($request->isMethod($method)) {
            return true;
        }
        throw new Exception('Route not found', 404);
    }

    public function getRequest()
    {
        return Request::createFromGlobals();
    }

    public function setRoutes($routes)
    {
        $this->routes = $routes;
    }

    public function next($controller, $action, Request $request)
    {
        $service = get_class_vars($controller);
        if (!empty($service['serviceClass'])) {
            $controller = new $controller(new $service['serviceClass']);
        } else {
            $controller = new $controller;
        }
        return $controller->{$action}($request);
    }

    private function getRoute(Request $request)
    {
        foreach ($this->routes as $route) {
            if (
                $route['path'] === $request->getPathInfo() &&
                $request->isMethod($route['method'])
            ) {
                return [
                    $route['controller'],
                    $route['action'],
                    $route['method']
                ];
            }
        }
        throw new Exception('Route not found');
    }
}
