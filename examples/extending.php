<?php

require '../vendor/autoload.php';

// Elsewhere in your project, you can now have your very own validation
// class where you can add custom rules and messages. This makes it
// much easier to inject dependencies (like a database object).

class Validator extends Violin\Violin
{
    protected $db;

    public function __construct(Database $db)
    {
        $this->db = $db; // Some database dependency

        // You can add a custom rule message here if you like, or, you
        // could add it outside of this validation class when you
        // make use of your new Validator object (see below).
        $this->addRuleMessage('usernameDoesNotExist', 'That username is taken');
    }

    // Prepend your validation rule name with validate_
    public function validate_usernameDoesNotExist($field, $value)
    {
        if($db->where('username', '=', $value)->count()) {
            return false; // Return false to fail validation
        }
    }
}

$v = new Validator;

// You could also add your rules messages down here if you fancy.
// $v->addRuleMessage('usernameDoesNotExist', 'That username is taken');

$v->validate([
    'username' => 'billy'
], [
    'username' => 'required|usernameDoesNotExist',
]);

if($v->valid()) {
    echo 'Valid!';
} else {
    echo '<pre>', var_dump($v->errors()), '</pre>';
}
