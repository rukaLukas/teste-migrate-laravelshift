<?php

namespace App\Exceptions;

use Illuminate\Validation\ValidationException;

class GeneralValidationException extends \Exception
{
   protected mixed $errors;

   public function validationException(): ValidationException
   {
       $errors = is_null($this->errors) ? [$this->message] : $this->errors;
       $errorMessages[0] = array_merge($errors);
       return ValidationException::withMessages($errorMessages);
   }

   public function getError()
   {
        $errors = is_null($this->errors) ? [$this->message] : $this->errors;
        return $errors;
   }
}

