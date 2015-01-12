<?php

namespace Violin\Validator;

use Violin\Rules\All;

class Validator
{
    /**
     * All rule messages
     *
     * @var array
     */
    public $ruleMessages = [
        'required' => '%s is required',
        'int' => '%s must be a number',
        'bool' => '%s must be true/false',
        'alpha' => '%s must be letters only',
        'alphaDash' => '%s must be letters, with - and _ permitted.',
        'alnum' => '%s must be letters and numbers only.',
        'alnumDash' => '%s must be letters and numbers, with - and _ permitted.',
        'email' => '%s must be a valid email address.',
        'activeUrl' => '%s must be an active URL.',
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
     * Custom defined rules
     *
     * @var array
     */
    protected $customRules;

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
        $rule = $this->extractRuleName($method);

        // Holds what method and arguments we want to call to validate.
        $toCall = null;

        // Check if a custom rule has been defined and if so, call it
        // and check if it's valid, adding an error if required.
        if (method_exists($this, 'validate_' . $method)) {
            $toCall = [$this, 'validate_' . $method];
        } else {
            $ruleClass = 'Violin\\Rules\\' . ucfirst($rule);

            if (class_exists($ruleClass)) {
                // Create a new instance of the internal rule class.
                $ruleClass = new $ruleClass();
                $toCall = [$ruleClass, 'run'];
            } else {
                $toCall = $this->customRules[$rule];
            }
        }

        // If we've found a method to call, call it with arguments
        // and check if the validation didn't pass.
        if ($toCall) {
            $this->callAndValidate($rule, $toCall, $args);
        }
    }

    /**
     * Call and validate a single rule and data
     *
     * @param  string $rule
     * @param  array $toCall
     * @param  array $args
     * @return void
     */
    protected function callAndValidate($rule, $toCall, $args)
    {
        $valid = call_user_func_array($toCall, $args);

        if (!$valid && $valid !== null) {
            $this->error($rule, $args);
        }
    }

    /**
     * Extract the rule name
     *
     * @param  string $method
     * @return string
     */
    protected function extractRuleName($method)
    {
        // Extract the possible internal class name
        // to look for a validation rule later.
        $rule = explode('validate_', $method);

        return end($rule);
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
        $message = isset($this->fieldMessages[$field][$messageKey])
            ? $this->fieldMessages[$field][$messageKey]
            : $this->ruleMessages[$messageKey];

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
