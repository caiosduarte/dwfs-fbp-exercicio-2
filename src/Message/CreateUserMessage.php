<?php

namespace App\Message;

use App\Entity\User;

final class CreateUserMessage implements UserMessage
{
    private $user;
    private $id;

    public function __construct(User $user, $id = null)
    {
        $this->user = $user;
        $this->id = $id;
    }

    public function getId() 
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

}
