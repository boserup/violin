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
            $fieldRules = explode('|', $rules[$name]);

            // Loop each requested rule
            foreach ($fieldRules as $rule) {
                $this->$rule($name, $value);
            }
        }
    }
}
