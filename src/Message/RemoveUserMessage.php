<?php

namespace App\Message;

final class RemoveUserMessage implements UserMessage
{
    /*
     * Add whatever properties & methods you need to hold the
     * data for this message class.
     */
     private $id;

     public function __construct(string $id)
     {
         $this->id = $id;
     }

    public function getId(): int
    {
        return $this->id;
    }
}
