<?php

namespace App\Error;



class AppError extends \Exception
{
    public $message;
    public $statusCode;

    public function __construct(string $message, $statusCode = 400)
    {        
        $this->message = $message;
        $this->statusCode = $statusCode;
    }
}