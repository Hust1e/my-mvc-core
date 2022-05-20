<?php

namespace Doppel\PhpMvcCore;

use Doppel\PhpMvcCore\middlewares\BaseMiddleware;

class Controller
{
    public string $layout = 'main';
    public static $action = '';
    /**
     * @var BaseMiddleware[]
     */
    protected array $middlewares = [];

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function render($views, $params = [])
    {
        return Application::$app->view->renderView($views, $params);
    }

    public function registerMiddleware(BaseMiddleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }

}