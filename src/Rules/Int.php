<?php

namespace Violin\Rules;

use Violin\Validator\BaseValidator;

class Int extends BaseValidator
{
    public static function run($name, $value)
    {
        if (!is_int($value)) {
            self::$errors[] = "{$name} must be an integer";
        }
    }
}
