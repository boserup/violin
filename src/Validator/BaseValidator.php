<?php

namespace Violin\Validator;

use Violin\Rules;

class BaseValidator
{
	protected static $errors;

	public function __call($method, $args)
	{
		$method = explode('validate_', $method);
		$method = ucfirst(end($method));

		$class = 'Violin\Rules\\' . $method;

		if(is_callable($class, $method)) {
			call_user_func_array([$class, 'run'], $args);
		}
	}

	protected function error($message)
	{
		self::$errors[] = $message;
	}

	public function errors()
	{
		return self::$errors;
	}
}