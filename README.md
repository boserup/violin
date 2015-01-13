# violin

Violin is an easy to use, highly customisable PHP validator.

**Note: This package is under heavy development and is not recommended for production.**

## Installing

Install using Composer.

```json
{
    "require": {
        "alexgarrett/violin": "1.*"
    }
}
```

## Basic usage

```php
use Violin\Violin;

$v = new Violin;

$v->validate([
    'name' => 'billy',
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
```

## Adding custom rules

Adding custom rules is simple. If the closure returns false, the rule fails.

```php
$v->addRuleMessage('isBanana', '%s expects banana, found "%s" instead.');

$v->addRule('isBanana', function($field, $value) {
    return $value === 'banana';
});
```

## Adding custom error messages

You can add rule messages, or field messages for total flexibility.

### Adding a rule message

```php
$v->addRuleMessage('required', 'You better fill in the %s field, or else.');
```

### Adding rule messages in bulk

```php
$v->addRuleMessages([
    'required' => 'You better fill in the %s field, or else.',
    'int' => 'The %s needs to be an integer, but I found %s.',
]);
```

### Adding a field message

Any field messages you add are preferred over any default or custom rule messages.

```php
$v->addFieldMessage('username', 'required', 'You need to enter a username to sign up.');
```

### Adding field messages in bulk

```php
$v->addFieldMessages([
    'username' => [
        'required' => 'You need to enter a username to sign up.'
    ],
    'age' => [
        'required' => 'I need your age.',
        'int' => 'Your age needs to be an integer.',
    ]
]);
```

### Error output

Errors are output categorised by field, so you're free to play around with them in whatever way you need them output.

```text
array(2) {
  ["name"]=>
  array(1) {
    [0]=>
    string(16) "name is required"
  }
  ["age"]=>
  array(2) {
    [0]=>
    string(15) "age is required"
    [1]=>
    string(20) "age must be a number"
  }
}
```

## Extending the Violin class

You can extend Violin to implement your own validation class and add rules, custom rule messages and custom field messages.

```php
class Validator extends Violin\Violin
{
    protected $db;

    protected function __construct(Database $db)
    {
        $this->db = $db; // Some database dependency

        // You can add a custom rule message here if you like, or, you
        // could add it outside of this validation class when you
        // make use of your new Validator object.
        $this->addRuleMessage('usernameDoesNotExist', 'That username is taken');
    }

    // Prepend your validation rule name with validate_
    public function validate_usernameDoesNotExist($field, $value)
    {
        if($db->where('username', '=', $value)->count()) {
            return false;
        }
    }
}

$v = new Validator;

// ... and so on.
```

## Rules

This list of rules are **in progress**. Of course, you can always contribute to the project if you'd like to add more to the base ruleset.

#### activeUrl

If the URL provided is an active URL using checkdnsrr().

#### alnum

If the value is alphanumeric.

#### alnumDash

If the value is alphanumeric. Dashes and underscores are permitted.

#### alpha

If the value is alphabetic letters only.

#### alphaDash

If the value is alphabetic letters only. Dashes and underscores are permitted.

#### bool

If the value is a boolean.

#### email

If the value is a valid email.

#### int

If the value is an integer, including integers within strings. 1 and '1' are both classed as integers.

#### required

If the value is present.

## Contributing

Please file issues under GitHub, or submit a pull request if you'd like to directly contribute.

## Todo

* Allow parameter based rules. For example:

```php
$v->validate([
    'name' => 'billy',
    'age' => '20'
], [
    'name' => 'required',
    'age' => 'required|int|max:21'
]);
```

* Improve error storage so `errors[]` doesn't contain a list of strings, but a list of messages to look up. Rough example:

```php
protected $errors = [
    'name' => [ // field
        'args' => [
            'FIELD_NAME', 'FIELD_VALUE'
        ],
        'errors' => [
            'required', 'alpha' // errors that have occured
        ]
    ]
];
```

Then when calling `errors()`, this should collect up errors for that field, build the string and return them.

* Allow errors to be defined with {field} and {value} rather than %s's for greater flexibility.
