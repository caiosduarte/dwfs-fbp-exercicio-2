<?php

namespace App\Message;

interface UserMessage
{
    /*
     * Add whatever properties & methods you need to hold the
     * data for this message class.
     */

    public function __construct(string $id);
    public function getId(): int;
}
