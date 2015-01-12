<?php

require '../vendor/autoload.php';

use Violin\Violin;

$v = new Violin;

// Adding a 'field' message over a 'rule' message allows you to
// customise errors for fields if you need to be more specific.
$v->addFieldMessage('username', 'required', 'You need to enter a username to sign up.');

$v->validate([
    'name' => 'Billy',
    'username' => ''
], [
    'name' => 'required',
    'username' => 'required'
]);

if($v->valid()) {
    echo 'Valid!';
} else {
    echo '<pre>', var_dump($v->errors()), '</pre>';
}
