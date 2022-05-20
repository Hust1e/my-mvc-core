<?php

namespace Doppel\PhpMvcCore;

abstract class UserModel extends DbModel
{
    abstract public function getDisplayName(): string;
}