<?php
namespace App\SpecValidators;

use App\SpecValidators\Rule;
use App\Specifications\Users\ChildSpecification;
use App\Specifications\NationalitySpecification;
use Illuminate\Database\Eloquent\Model;

class SpecValidator
{
    protected $validations;
    protected $errors = [];

    protected function add(string $name, Rule $rule)
    {
        $this->validations[$name] = $rule;
    }

    protected function remove(string $name)
    {
        unset($this->validations[$name]);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function validate(Model $obj)
    {
        foreach ((array)$this->validations as $key => $value) {
            $validation = $value;
            if (!$validation->validate($obj)) {
                $this->errors[] = $validation->errorMessage;
            }
        }

        return $this;
    }

    public function isValid()
    {
        return $this->errors == null;
    }
}
