<?php

namespace App\Controller;

use App\Message\GetUserMessage;
use App\Message\RemoveUserMessage;
use App\Message\ListUsersMessage;
use Symfony\Component\Validator\Validator\ValidatorInterface;



use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\DeserializeUserService;
use App\Service\SerializeUserService;
use App\Service\UpdateUserService;
use App\Service\CreateUserService;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Envelope;

class UserController
{
    private MessageBusInterface $bus;
    private EntityManagerInterface $manager;
    private ValidatorInterface $validator;

    public function __construct(EntityManagerInterface $manager, 
        ValidatorInterface $validator, 
        MessageBusInterface $bus)
    {
        $this->manager = $manager;
        $this->validator = $validator;
        $this->bus = $bus;
    }

    /**
     * @Route("/users/{id}", methods={"DELETE"})
     */
    public function delete(int $id) 
    {        
        $this->bus->dispatch(new RemoveUserMessage($id));
        
        return new JsonResponse([], Response::HTTP_NO_CONTENT);        
    }    

    /**
     * @Route("/users/{id}", methods={"PUT"})
     */
    public function update(Request $request, int $id) {

        $data = json_decode($request->getContent(), true);
        $data['id'] = $id;

        $updateUserService = new UpdateUserService($this->manager, $this->validator);
        $user = $updateUserService->execute(DeserializeUserService::execute($data));

        return new JsonResponse(SerializeUserService::execute($user), Response::HTTP_OK);        
    }

    /**
     * @Route("/users", methods={"POST"})
     */
    public function create(Request $request): Response 
    {
        $data = json_decode($request->getContent(), true);

        $updateUserService = new CreateUserService($this->manager, $this->validator);
        $user = $updateUserService->execute(DeserializeUserService::execute($data));

        return new JsonResponse(SerializeUserService::execute($user), Response::HTTP_CREATED, [            
            "Location" => $request->getUriForPath("/users/" . $user->getId())
        ]);
    }

    /**
     * @Route("/users", methods={"GET"})
     */
    public function index(): Response 
    {
        $wrapper =  $this->bus->dispatch(new ListUsersMessage()); 

        return new JsonResponse($this->getSerializedFromWrapper($wrapper), Response::HTTP_OK);
    } 

    /**
     * @Route("/users/{id}")
     */
    public function get(int $id): Response 
    {
        $wrapper =  $this->bus->dispatch(new GetUserMessage($id));        
                
        return new JsonResponse($this->getSerializedFromWrapper($wrapper));
    }    

    private function getSerializedFromWrapper(Envelope $wrapper): array {
        $handled = $wrapper->last(HandledStamp::class);    
        $result = $handled->getResult();

        $serialized = null;
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