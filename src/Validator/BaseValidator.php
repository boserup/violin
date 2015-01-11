<?php

namespace Violin\Validator;

use Violin\Rules\All;

class BaseValidator
{
    public $ruleMessages = [
        'required' => '%s is required',
        'int' => '%s must be an integer'
    ];

    protected $errors;

    public function __call($method, $args)
    {
        $internalMethod = explode('_', $method);
        $internalMethod = end($internalMethod);

        $class = 'Violin\\Rules\\' . ucfirst($internalMethod);

        if (class_exists($class)) {
            $class = new $class();

            $valid = call_user_func_array([$class, 'run'], $args);

            if (!$valid) {
                $this->error($internalMethod, $args);
            }
        }
    }

    public function errors()
    {
        return $this->errors;
    }

    public function error($messageKey, $args)
    {
        $this->errors[] = vsprintf($this->ruleMessages[$messageKey], $args);
    }

    public function valid()
    {
        return empty($this->errors);
    }

    public function addRuleMessage($rule, $message)
    {
        $this->ruleMessages[$rule] = $message;
    }

    public function addRuleMessages(array $messages)
    {
        $this->ruleMessages = $messages;
    }
}
