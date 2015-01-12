<?php

namespace Violin\Validator;

class MessageBag
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
     * Get all errors
     * 
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
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
