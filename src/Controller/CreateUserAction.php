<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Envelope;

use App\Service\DeserializeUserService;
use App\Service\SerializeUserService;

use App\Message\CreateUserMessage;

use App\Service\GetObjectsFromWrapper;
use GetObjectsFromWrapper as GlobalGetObjectsFromWrapper;

final class CreateUserAction 
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @Route("/users", methods={"POST"})
    */
    public function __invoke(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $wrapper = $this->bus->dispatch(new CreateUserMessage(DeserializeUserService::execute($data)));     

        $user = GlobalGetObjectsFromWrapper::execute($wrapper);

        return new JsonResponse($this->getSerializedFromWrapper($wrapper), Response::HTTP_CREATED, [            
            "Location" => $request->getUriForPath("/users/" . $user->getId())
        ]); 
    }

    private function getSerializedFromWrapper(Envelope $wrapper) {
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