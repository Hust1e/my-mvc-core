<?php

namespace Doppel\PhpMvcCore\exception;

class ForbiddenException extends \Exception
{
    protected $code = 403;
    protected $message = "You dont have permission to access this page";
}