<?php
namespace app\core;

class Router {

    public Request $request;
    public Response $response;
    protected array $routes = [];

    public function __construct(Request $request,Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path,$callback)
    {
        $this->routes['get'][$path] = $callback;
    }
    
    public function post($path,$callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false) {
            return $this->response->jsonOut(404,["message"=>"Not Found"]);
        }

        if (is_array($callback)) {
            $callback[0] = new $callback[0]();
        }

        return call_user_func($callback,$this->request);
    }
}