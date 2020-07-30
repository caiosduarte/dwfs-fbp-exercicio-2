<?php

namespace App\Controller;

use Symfony\Component\Validator\Validator\ValidatorInterface;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\DeleteUserService;
use App\Service\DeserializeUserService;
use App\Service\SerializeUserService;
use App\Service\GetUserService;
use App\Service\UpdateUserService;
use App\Service\CreateUserService;
use App\Service\ListUsersService;

class UserController
{
    private EntityManagerInterface $manager;
    private ValidatorInterface $validator;

    public function __construct(EntityManagerInterface $manager, 
        ValidatorInterface $validator)
    {
        $this->manager = $manager;
        $this->validator = $validator;
    }

    /**
     * @Route("/users/{id}", methods={"DELETE"})
     */
    public function delete(int $id) 
    {
        $deleteUserService = new DeleteUserService($this->manager);
        $deleteUserService->execute($id);

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
        $users = (new ListUsersService($this->manager))->execute();
        $data = [];

        foreach($users as $user)
        {
            $data[] = SerializeUserService::execute($user);
        }

        return new JsonResponse($data, Response::HTTP_OK);
    } 

    /**
     * @Route("/users/{id}")
     */
    public function get(int $id): Response 
    {
        $getUserService = new GetUserService($this->manager);
        $user = $getUserService->execute($id);

        return new JsonResponse(SerializeUserService::execute($user));
    }    
}