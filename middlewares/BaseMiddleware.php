<?php

namespace Doppel\PhpMvcCore\middlewares;

abstract class BaseMiddleware
{
    abstract public function execute();
}