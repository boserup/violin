# violin

Violin is an easy to use, highly customisable PHP validator.

**Note: This package is under heavy development and is not recommended for production**

## Installing

Install using Composer.

```json
{
    "alexgarrett/violin": "dev-master"
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

### Adding rule messages

```php
$v->addRuleMessage('required', 'You better fill in the %s field, or else.');
```

### Adding field messages

```php
$v->addFieldMessage('username', 'required', 'You need to enter a username to sign up.');
```

## Extending the Violin class

```php
class Validator extends Violin\Violin
{
    protected $db;

    protected function __construct(Database $db)
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
            return false;
        }
    }
}
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
