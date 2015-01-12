<?php

namespace Violin\Rules;

class Alnum
{
    /**
     * Run the validation
     *
     * @param  string $name
     * @param  mixed $value
     * @return bool
     */
    public function run($name, $value)
    {
        return preg_match('/^[\pL\pM\pN]+$/u', $value);
    }
}
