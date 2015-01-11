<?php

namespace Violin;

use Violin\Validator\BaseValidator;

class Violin extends BaseValidator
{
	public function validate($fields, $rules)
	{
		foreach($fields as $name => $value) {
			$rules = explode('|', $rules[$name]);
			
			foreach($rules as $rule) {
				$method = 'validate_' . $rule;

				if($this->methodExists($method)) {
					$this->$method($name, $value);
				} else {
					$this->$rule($name, $value);
				}
			}
		}
	}

	public function valid()
	{
		return empty(parent::$errors);
	}

	protected function methodExists($method)
	{
		return is_callable([$this, $method]);
	}
}