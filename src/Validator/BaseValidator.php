<?php

namespace Violin\Validator;

use Violin\Rules\All;

class BaseValidator
{
    public $messages = [
        'required' => '%s is required',
        'int' => '%s must be an integer'
    ];

    protected $errors;

    public function __call($method, $args)
    {
        $method = explode('_', $method);
        $method = end($method);

        $class = 'Violin\\Rules\\' . ucfirst($method);
        if (class_exists($class)) {
            $class = new $class();

            $valid = call_user_func_array([$class, 'run'], $args);

            if (!$valid) {
                $this->error(vsprintf($this->messages[$method], $args));
            }
        }
    }

    public function errors()
    {
        return $this->errors;
    }

    public function error($message)
    {
        $this->errors[] = $message;
    }

    public function valid()
    {
        return empty($this->errors);
    }

    public function addMessage($rule, $message)
    {
        $this->messages[$rule] = $message;
    }
}
