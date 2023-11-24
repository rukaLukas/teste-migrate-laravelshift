<?php
namespace App\Validations\User;

use App\SpecValidators\Rule;
use App\SpecValidators\SpecValidator;
use App\Specifications\UniqueCpfSpecification;
use App\Specifications\Users\UniqueEmailSpecification;

class UserEnabledToUpdate extends SpecValidator
{
    public function __construct()
    {
    }
}
