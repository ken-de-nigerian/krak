<?php

namespace Fir\Libraries;
/**
 * 
 */
class Validator{

    protected mixed $_db;
    protected ErrorHandler $errorHandler;
    protected array $items;
    protected array $rules = ['required', 'minlength', 'maxlength', 'email', 'alnum', 'alpha', 'digit', 'float', 'match', 'unique'];

    public array $messages = [
        'required' => 'The :field field is required',
        'minlength' => 'The :field field must be at least :satisfier characters long',
        'maxlength' => 'The :field field must not exceed :satisfier characters in length',
        'email' => 'The :field field must be a valid email address',
        'alnum' => 'The :field field must contain only alphanumeric characters',
        'alpha' => 'The :field field must contain only alphabetic characters',
        'digit' => 'The :field field must contain only digits',
        'float' => 'The :field field must be a valid float number',
        'match' => 'The :field field must match the :satisfier field',
        'unique' => 'The :field field is already taken'
    ];
	
	public function __construct($db = null) {
        $this->_db =  $db;
        $this->errorHandler = new ErrorHandler();
    }
	
	public function check(array $items, array $rules): Validator
    {
        $this->items = $items;

        foreach ($items as $item => $value) {
            if (array_key_exists($item, $rules)) {
                $this->validate([
                    'field' => $item,
                    'value' => $value,
                    'rules' => $rules[$item]
                ]);
            }
        }

        return $this;
    }
	
	public function fails(): bool {
        return $this->errorHandler->hasErrors();
    }

    public function errors(): ErrorHandler {
        return $this->errorHandler;
    }
	
	protected function validate(array $item): void
    {
        $field = $item['field'];
        foreach ($item['rules'] as $rule => $satisfier) {
            if (in_array($rule, $this->rules)) {
                if (!call_user_func_array([$this, $rule], [$field, $item['value'], $satisfier])) {
                    $this->errorHandler->addError(
                        str_replace([':field',':satisfier'], [$field, $satisfier], $this->messages[$rule]),
                        $field
                    );
                }
            }
        }
    }

	protected function required($field, $value, $satisfier): bool
    {
        return !empty(trim($value));
    }

    protected function minlength($field, $value, $satisfier): bool
    {
        return mb_strlen($value) >= $satisfier;
    }

    protected function maxlength($field, $value, $satisfier): bool
    {
        return mb_strlen($value) <= $satisfier;
    }

    protected function email($field, $value, $satisfier)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    protected function alnum($field, $value, $satisfier): bool
    {
        return ctype_alnum($value);
    }

    protected function alpha($field, $value, $satisfier): bool
    {
        return ctype_alpha($value);
    }

    protected function digit($field, $value, $satisfier): bool
    {
        return ctype_digit($value);
    }

    protected function float($field, $value, $satisfier): float
    {
        return filter_var($value, FILTER_VALIDATE_FLOAT) !== false;
    }

    protected function match($field, $value, $satisfier): bool
    {
        return $value === $this->items[$satisfier];
    }

	protected function unique($field, $value, $satisfier): bool {
        $q1 = $this->_db->has("user", ["AND" => [$field => $value]]);
        if($q1){
            return true;
        }else{
            return false;
        }
	}
}