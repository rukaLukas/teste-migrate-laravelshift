<?php
namespace App\SpecValidators;

class Rule
{
    public $errorMessage;
    private $specification;

    public function __construct($specification, $errorMessage)
    {
        $this->specification = $specification;
        $this->errorMessage = $errorMessage;
    }

    public function validate(mixed $obj)
    {
        return $this->specification->isSatisfiedBy($obj);
    }
}