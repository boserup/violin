<?php

namespace Violin\Rules;

class AlphaDash
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
        return preg_match('/^[\pL\pM_-]+$/u', $value);
    }
}
