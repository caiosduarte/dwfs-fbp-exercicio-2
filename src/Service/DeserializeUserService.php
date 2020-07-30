<?php

namespace App\Service;

use App\Entity\User;

final class DeserializeUserService {

    public static function execute(array $userRequest): User 
    {
        $user = new User($userRequest['name'], $userRequest['email']);

        if (array_key_exists('id', $userRequest)) 
        {
            $user->setId($userRequest['id']);
        }        

        foreach($userRequest['telephones'] as $telephone) 
        {
            $user->addTelephone($telephone['number']);
        }          

        return $user;
    }    
}
    
