<?php

namespace App\Service;

use Symfony\Component\Messenger\Envelope;

use App\Service\GetObjectsFromWrapper;
use GetObjectsFromWrapper as GlobalGetObjectsFromWrapper;

final class GetSerializedFromWrapper 
{

    public static function execute(Envelope $wrapper) {
        $result = GlobalGetObjectsFromWrapper::execute($wrapper);

        $serialized = [];
        if(is_array($result)) {
            
            foreach($result as $user)
            {
                $serialized[] = SerializeUserService::execute($user);
            }           
        }
        else 
        {
            $serialized = SerializeUserService::execute($result);
        }

        return $serialized;
    }

}