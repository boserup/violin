<?php

namespace Violin\Rules;

class Int
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
        return is_int($value);
    }
}
