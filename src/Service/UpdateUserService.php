<?php

namespace App\Service;

use App\Entity\Telephone;
use Doctrine\ORM\EntityManagerInterface;



class UpdateUserService {
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function execute(UserRequest $userRequest): void 
    {
        /*
        $data = json_decode($request->getContent(), true);

        $user = $this->manager->getRepository(User::class)->findOneBy(['id' => $id]);

        if(!$user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);    
        }

        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->getTelephones()->clear();
        
        foreach($data['telephones'] as $telephone) 
        {
            $user->addTelephone($telephone['number']);
        }  

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            $violations = array_map(fn(ConstraintViolationInterface $violation) => [
                'property' => $violation->getPropertyPath(),
                'message' => $violation->getMessage()
            ], iterator_to_array($errors));
            return new JsonResponse($violations, Response::HTTP_BAD_REQUEST);
        }          

        try 
        {
            $this->manager->beginTransaction();      
            $this->manager->persist($user);          
            $this->manager->flush();
            $this->manager->commit();
        }
        catch(\Exception $ex) 
        {
            $this->manager->rollback();
            return new JsonResponse(['error' => 'Internal error: ' . $ex->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse($this->serializeUser($user), Response::HTTP_OK);  
        */
    }
}