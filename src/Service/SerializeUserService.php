<?php

namespace App\Service;

use App\Entity\User;

class SerializeUserService {

    public static function execute(array $userRequest): User 
    {
        $user = new User($userRequest['name'], $userRequest['email']);

        $user->setId($userRequest['id']);

        foreach($userRequest['telephones'] as $telephone) 
        {
            $user->addTelephone($telephone['number']);
        }          

        return $user;
    }
}
    
