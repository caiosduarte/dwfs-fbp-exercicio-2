<?php

namespace App\Controller;

use App\Message\GetUserMessage;
use App\Message\RemoveUserMessage;
use App\Message\ListUsersMessage;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use App\Service\GetSerializedFromWrapper;

use Symfony\Component\Messenger\MessageBusInterface;

class UserController
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
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
     * @Route("/users", methods={"GET"})
     */
    public function index(): Response 
    {
        $wrapper = $this->bus->dispatch(new ListUsersMessage()); 

        return new JsonResponse(GetSerializedFromWrapper::execute($wrapper), Response::HTTP_OK);
    } 

    /**
     * @Route("/users/{id}")
     */
    public function get(int $id): Response 
    {
        $wrapper =  $this->bus->dispatch(new GetUserMessage($id));        
                
        return new JsonResponse(GetSerializedFromWrapper::execute($wrapper), Response::HTTP_OK);
    }    


}