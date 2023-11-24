<?php
namespace App\Validations\User;

use App\SpecValidators\Rule;
use App\SpecValidators\SpecValidator;
use App\Specifications\Users\UniqueCPFSpecification;
use App\Specifications\Users\UniqueEmailSpecification;
use App\Specifications\Users\SecurePasswordSpecification;

class UserEnabledToSave extends SpecValidator
{
    public function __construct()
    {
        $uniqueEmail = new UniqueEmailSpecification();
        $uniqueCPF = new UniqueCPFSpecification();

        $this->add('uniqueEmail', new Rule($uniqueEmail, 'E-mail já cadastrado!'));
        $this->add('uniqueCPF', new Rule($uniqueCPF, 'CPF já cadastrado!'));
    }
}
