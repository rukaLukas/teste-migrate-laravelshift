<?php
namespace App\Exceptions;

use App\Exceptions\GeneralValidationException;

class EmUsoException extends GeneralValidationException
{
    public function __construct($id)
    {
        $this->message = "Registro $id já em uso ";
    }
}
