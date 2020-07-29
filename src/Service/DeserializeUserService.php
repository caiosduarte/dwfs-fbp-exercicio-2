<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Telephone;

final class DeserializeUserService {

    public static function execute(User $user): array 
    {
        $data = [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'telephones' => array_map(fn(Telephone $telephone) => 
                                             $telephone->getNumber(), 
                                            iterator_to_array($user->getTelephones())),
                'createdDate' => ($user->getCreatedDate()? $user->getCreatedDate()->format('d/m/Y H:i:s') : null)
        ];

        return $data;
    }
}
    
