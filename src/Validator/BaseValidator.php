<?php

namespace Violin\Validator;

use Violin\Rules\All;

class BaseValidator
{
    protected $errors;

    public function __call($method, $args)
    {
        $method = explode('_', $method);
        $method = ucfirst(end($method));

        $class = 'Violin\\Rules\\' . $method;
        if(class_exists($class)) {
            $class = new $class();

            $result = call_user_func_array([$class, 'run'], $args);

            $this->error($result);
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
}
