<?php

namespace app\core;

class Response
{
    public function getStatusCode(int $code)
    {
        http_response_code($code);
    }

    public function redirect(string $string)
    {
        header('Location: '.$string);
    }
}