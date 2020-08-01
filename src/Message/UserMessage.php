<?php

namespace App\Message;
use App\Entity\User;

interface UserMessage
{
    /*
     * Add whatever properties & methods you need to hold the
     * data for this message class.
     */

    public function __construct(User $user, string $id = null);
    public function getId();
    public function getUser(): User;
}
