<?php

namespace App\Error;


class AppError extends \Exception  
{
    private array $errors;

    public function __construct(string $message, $code = 400, $errors = [])
    {        
        $this->message = $message;
        $this->code = $code;
        $this->errors = $errors;
    }

    public function getErrors(): array {
        return $this->errors;
    }

}