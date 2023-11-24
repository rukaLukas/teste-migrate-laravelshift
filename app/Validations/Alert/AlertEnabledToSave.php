<?php
namespace App\Validations\Alert;

use App\SpecValidators\Rule;
use App\SpecValidators\SpecValidator;
use App\Specifications\UniqueCPFSpecification;
use App\Specifications\Alerts\UniqueAlertSpecification;

class AlertEnabledToSave extends SpecValidator
{
    public function __construct(int $id = null)
    {
        $uniqueAlert = new UniqueAlertSpecification($id);
        $uniqueCPFSpecification = new UniqueCPFSpecification($id);
        
        // $this->add('uniqueAlert', new Rule($uniqueAlert, 'Já existe um alerta cadastrado para os dados informados!'));
        // $this->add('uniqueCpf', new Rule($uniqueCPFSpecification, 'Já existe um alerta cadastrado para esse CPF!'));
    }
}