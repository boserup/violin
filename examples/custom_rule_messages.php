<?php

require '../vendor/autoload.php';

use Violin\Violin;

$v = new Violin;

$v->addRuleMessages([
    'required', 'You better fill in the %s field, or else.',
    'int', 'The %s needs to be an integer, but I found %s.',
]);

$v->validate([
    'name' => '',
    'age' => 20
], [
    'name' => 'required',
    'age' => 'required|int'
]);

if($v->valid()) {
    echo 'Valid!';
} else {
    echo '<pre>', var_dump($v->errors()), '</pre>';
}
