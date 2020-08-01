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

use App\Message\UpdateUserMessage;

use App\Service\GetObjectsFromWrapper;
use GetObjectsFromWrapper as GlobalGetObjectsFromWrapper;

final class UpdateUserAction 
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @Route("/users/{id}", methods={"PUT"})
    */
    public function __invoke(Request $request, int $id): Response
    {
        $data = json_decode($request->getContent(), true);
        $data['id'] = $id;

        $wrapper = $this->bus->dispatch(new UpdateUserMessage(DeserializeUserService::execute($data, $id)));     

        $user = GlobalGetObjectsFromWrapper::execute($wrapper);

        return new JsonResponse(SerializeUserService::execute($user), Response::HTTP_OK);   
    } 
}