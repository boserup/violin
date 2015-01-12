<?php

namespace Violin\Rules;

class Bool
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
        return is_bool($value);
    }
}
