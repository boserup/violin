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
        if (!is_int($value)) {
            return false;
        }

        return true;
    }
}
