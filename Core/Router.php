<?php
// File: /core/Router.php

class Router {
    private $routes = [];
     
    // Add a route with a specific method
    public function add($method, $route, $callback) {
        $route = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[a-zA-Z0-9_-]+)', $route);
        $route = '/^' . str_replace('/', '\/', $route) . '$/';
        
        $this->routes[] = [
            'method' => strtoupper($method),
            'route' => $route,
            'callback' => $callback
        ];
    }
    // $router->get('/petrol/api/stations', 'App\Controllers\Api\StationController');
    public function apiResource($route, $callback){
        $this->add('GET',$route, $callback.'@index');
        $this->add('POST',$route, $callback.'@store');
        $this->add('GET',$route."/{id}", $callback.'@show');
        $this->add('POST',$route."/{id}", $callback.'@update');
        $this->add('DELETE',$route."/{id}", $callback.'@destroy');
    }

    public function get($route, $callback){
        $this->add('GET',$route, $callback);
    }

    public function post($route, $callback){
        $this->add('POST',$route, $callback);
    }

    public function put($route, $callback){
        $this->add('PUT',$route, $callback);
    }

    public function patch($route, $callback){
        $this->add('PATCH',$route, $callback);
    }

    public function delete($route, $callback){
        $this->add('DELETE',$route, $callback);
    }


    // Dispatch the route
    public function dispatch($url, $requestMethod) {
        $requestMethod = strtoupper($requestMethod);
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && preg_match($route['route'], $url, $matches)) {
                // Remove numeric keys from $matches, leaving only named parameters
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                
                if (is_callable($route['callback'])) {
                    call_user_func_array($route['callback'], $params);
                } elseif (is_string($route['callback'])) {
                    $this->callController($route['callback'], $params);
                }
                return;
            }
        }

        // 404 Error handling
        http_response_code(404);
        echo "404 Not Found";
    }

    // Call a controller method
    private function callController($controllerAction, $params) {
        list($controller, $action) = explode('@', $controllerAction);
       // $controller = 'App\\Controllers\\' . $controller;
        if (class_exists($controller)) {
            $controllerObject = new $controller();
            if (method_exists($controllerObject, $action)) {
                call_user_func_array([$controllerObject, $action], $params);
            } else {
                throw new Exception("Method $action not found in controller $controller");
            }
        } else {
            throw new Exception("Controller $controller not found");
        }
    }
}
