<?php

namespace Violin\Validator;

use Violin\Rules\All;

class BaseValidator
{
    /**
     * All rule messages
     *
     * @var array
     */
    public $ruleMessages = [
        'required' => '%s is required',
        'int' => '%s must be an integer'
    ];

    /**
     * All field messages
     *
     * @var array
     */
    public $fieldMessages;

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
        // Check if a custom rule has been defined and if so, call it
        // and check if it's valid, adding an error if required.
        if (method_exists($this, 'validate_' . $method)) {
            $valid = call_user_func_array([$this, 'validate_' . $method], $args);

            // Log an error if it's not valid
            if (!$valid) {
                $this->error($method, $args);
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
        $field = $args[0];

        // If a field message has been set, we use this as preference.
        // Otherwise, we use the standard rule messages.
        if (isset($this->fieldMessages[$field][$messageKey])) {
            $message = $this->fieldMessages[$field][$messageKey];
        } else {
            $message = $this->ruleMessages[$messageKey];
        }

        // Extract the message from the ruleMessages array, passing in
        // the arguments to replace %s's if required, and return it.
        $this->errors[$field][] = vsprintf($message, $args);
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
     * Adds a custom rule message.
     *
     * @param string $rule
     * @param string $message
     */
    public function addRuleMessage($rule, $message)
    {
        $this->ruleMessages[$rule] = $message;
    }

    /**
     * Adds custom rule messages.
     *
     * @param array $messages
     */
    public function addRuleMessages(array $messages)
    {
        $this->ruleMessages = $messages;
    }

    /**
     * Adds a custom field message.
     *
     * @param string $field
     * @param string $rule
     * @param string $message
     */
    public function addFieldMessage($field, $rule, $message)
    {
        $this->fieldMessages[$field][$rule] = $message;
    }

    /**
     * Adds custom field messages
     *
     * @param array $messages
     */
    public function addFieldMessages(array $messages)
    {
        $this->fieldMessages = $messages;
    }
}
