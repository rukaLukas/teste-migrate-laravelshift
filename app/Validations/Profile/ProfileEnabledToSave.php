<?php
namespace App\Validations\Profile;

use App\SpecValidators\Rule;
use App\SpecValidators\SpecValidator;
use App\Specifications\Profiles\UniqueNameSpecification;

class ProfileEnabledToSave extends SpecValidator
{
    public function __construct()
    {
        $uniqueName = new UniqueNameSpecification();
        
        $this->add('uniqueName', new Rule($uniqueName, 'Perfil com esse nome já cadastrado!'));
    }
}