<?php
namespace app\core;
use app\core\exception\NotFoundException;

class Router
{
    protected array $routes = [];
    public Request $request;
    public Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->response = $response;
        $this->request = $request;
    }
    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }
    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;
        if($callback === false)
        {
            throw new NotFoundException();
        }
        if(is_string($callback))
        {
            return Application::$app->view->renderView($callback);
        }
        if(is_array($callback))
        {
            Application::$app->controller = new $callback[0];
            Application::$app->controller->action = $callback[1];
            $callback[0] = Application::$app->controller;

            foreach (Application::$app->controller->getMiddlewares() as $middleware)
            {
                $middleware->execute();
            }
        }
        return call_user_func($callback, $this->request, $this->response);
    }
}