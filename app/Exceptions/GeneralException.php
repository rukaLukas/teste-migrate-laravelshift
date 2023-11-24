<?php
namespace App\Exceptions;

class GeneralException extends GeneralValidationException
{
    public function __construct(array $messages)
    {
        $this->errors = $messages;
        $this->message = implode(",", $messages);
    }
}
