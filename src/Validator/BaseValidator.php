<?php

namespace Violin\Validator;

use Violin\Rules\All;

class BaseValidator
{
    /**
     * All default rule messages
     * 
     * @var array
     */
    public $ruleMessages = [
        'required' => '%s is required',
        'int' => '%s must be an integer'
    ];

    /**
     * Accumulated errors
     * 
     * @var array
     */
    protected $errors;

    /**
     * Checks if an internal class for request validation exists,
     * and if so, runs it with arguments and reports an error.
     * 
     * @param  string   $method
     * @param  array    $args
     * 
     * @return void
     */
    public function __call($method, $args)
    {
        // Extract the possible internal class name
        // to look for a validation rule.
        $rule = explode('_', $method);
        $rule = end($rule);

        $ruleClass = 'Violin\\Rules\\' . ucfirst($rule);

        if (class_exists($ruleClass)) {

            // Create a new instance of the internal rule class.
            $ruleClass = new $ruleClass();

            // Call the run method on the internal rule class,
            // passing in the arguments (field and value).
            $valid = call_user_func_array([$ruleClass, 'run'], $args);

            // Log an error if it's not valid
            if (!$valid) {
                $this->error($rule, $args);
            }
        }
    }

    /**
     * Gets the list of accumulated errors
     * 
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Adds an error to the list of messages
     * 
     * @param  string $messageKey
     * @param  array $args
     * @return void
     */
    public function error($messageKey, $args)
    {
        // Extract the message from the ruleMessages array, passing
        // in the arguments to replace %s's if required.
        $message = vsprintf($this->ruleMessages[$messageKey], $args);

        $this->errors[] = $message;
    }

    /**
     * Checks if validation has passed.
     * 
     * @return bool
     */
    public function valid()
    {
        return empty($this->errors);
    }

    /**
     * Adds a custom rule message
     * 
     * @param string $rule
     * @param string $message
     */
    public function addRuleMessage($rule, $message)
    {
        $this->ruleMessages[$rule] = $message;
    }

    /**
     * Adds custom rule messages
     * 
     * @param array $messages
     */
    public function addRuleMessages(array $messages)
    {
        $this->ruleMessages = $messages;
    }
}
