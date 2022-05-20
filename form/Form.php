<?php

namespace Doppel\PhpMvcCore\form;

use Doppel\PhpMvcCore\Model;

class Form
{
    public static function begin(string $action, string $method)
    {
        echo sprintf('<form action="%s" method="%s">', $action, $method);
        return new Form();
    }
    public static function end()
    {
        echo '</form>';
    }
    public function field(Model $model, $attribute)
    {
        return new Field($model, $attribute);
    }
}