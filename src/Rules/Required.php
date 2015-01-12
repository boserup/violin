<?php

namespace Violin\Rules;

class Required
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
        return !empty($value);
    }
}
