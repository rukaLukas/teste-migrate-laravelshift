<?php
namespace App\Exceptions;

class NaoEncontradaException extends GeneralValidationException
{
    public function __construct($id = null)
    {
        $this->message = is_null($id) ? "Não existe registro" : "Não existe registro com o código $id";
    }
}
