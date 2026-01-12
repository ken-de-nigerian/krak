<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\libraries;

/**
 * 
 */
class ErrorHandler{
	
	protected array $errors = [];
	
	public function addError($error, $key = null): void
    {
		if ($key)  {
			$this->errors[$key][] = $error;
		}

		else {
			$this->errors[] = $error;
		}
	}
	
	public function all($key = null) {
	   return $this->errors[$key] ?? $this->errors;
	}
	
	public function hasErrors(): bool {
        return (bool)count($this->all());
	}
	
	public function first($key) {
        return isset($this->all()[$key][0]) ? $this->all()[$key][0] : '';
	}
}