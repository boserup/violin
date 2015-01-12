<?php

namespace Violin\Validator;

use Violin\Rules\All;
use Violin\Validator\MessageBag;

class Validator
{
    /**
     * Custom defined rules
     * 
     * @var array
     */
    protected $customRules;

    /**
     * Message bag
     * 
     * @var Violin\MessageBag
     */
    protected $messages;

    public function __construct()
    {
        $this->messages = new MessageBag;
    }

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
        // Check if a custom rule has been defined and if so, call it
        // and check if it's valid, adding an error if required.
        if (method_exists($this, 'validate_' . $method)) {
            $valid = call_user_func_array([$this, 'validate_' . $method], $args);

            // Log an error if it's not valid
            if (!$valid) {
                $this->messages->error($method, $args);
            }

            return;
        }

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
                $this->messages->error($rule, $args);
            }
        } else if (isset($this->customRules[$rule])) {
            // Otherwise, we might have a custom added rule not defined
            // within a class that extends Violin. Call it here.
            
            $valid = $this->customRules[$rule]($args[0], $args[1]);

            // Log an error if it's not valid
            if (!$valid) {
                $this->messages->error($rule, $args);
            }
        }
    }

    /**
     * Adds a new rule
     * 
     * @param string $name
     * @param Closure $callback
     */
    public function addRule($name, $callback)
    {
        $this->customRules[$name] = $callback;
    }

    /**
     * Checks if validation has passed.
     *
     * @return bool
     */
    public function valid()
    {
        return empty($this->messages->getErrors());
    }

    /**
     * Gets the list of accumulated errors
     *
     * @return array
     */
    public function errors()
    {
        return $this->messages->getErrors();
    }
}
