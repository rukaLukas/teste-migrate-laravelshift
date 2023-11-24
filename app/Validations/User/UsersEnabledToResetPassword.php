<?php
namespace App\Validations\User;

use App\SpecValidators\Rule;
use App\SpecValidators\SpecValidator;
use App\Specifications\Users\ChildSpecification;
use App\Specifications\Users\UniqueEmailSpecification;
use App\Specifications\Users\SecurePasswordSpecification;

class UsersEnabledToResetPassword extends SpecValidator
{
    public function __construct()
    {
        $securePassword = new SecurePasswordSpecification();
        
        $this->add('securePassword', new Rule($securePassword, 'A Senha deve ter mínimo de oito caracteres, ao menos uma letra, um número e um caracter especial'));
    }
}