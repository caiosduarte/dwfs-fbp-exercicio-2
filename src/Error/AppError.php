<?php

namespace App\Error;



class AppError extends \Exception
{
    public function __construct(string $message, $code = 400)
    {        
        $this->message = $message;
        $this->code = $code;
    }

}