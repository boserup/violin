<?php

namespace Violin;

use Violin\Validator\BaseValidator;

class Violin extends BaseValidator
{
    /**
     * Kicks off validation by calling a method like validate_required.
     * Will invoke __call magic method on BaseValidator to look for
     * internal validation rules.
     *
     * @param  array $fields
     * @param  array $rules
     * @return void
     */
    public function validate($fields, $rules)
    {
        // Loop each requested validation field
        foreach ($fields as $name => $value) {
            // Get rules, which are originally
            // seperated by a pipe.
            $rules = explode('|', $rules[$name]);

            // Loop each requested rule
            foreach ($rules as $rule) {
                // Custom method names will start with validate_
                // so we check if this is callable first.
                $method = 'validate_' . $rule;

                if ($this->methodExists($method)) {
                    $this->$method($name, $value);
                }
            }
        }
    }

    /**
     * Checks if a method is callable
     *
     * @param  string $method
     * @return bool
     */
    protected function methodExists($method)
    {
        return is_callable([$this, $method]);
    }
}
