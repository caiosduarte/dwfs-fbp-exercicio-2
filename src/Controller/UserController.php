<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use App\Entity\Telephone;
use App\Entity\User;

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

    private function serializeUser(User $user) 
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

    /**
     * @Route("/users/{id}", methods={"DELETE"})
     */
    public function delete(int $id) 
    {
        try 
        {
            $user = $this->manager->getRepository(User::class)->find($id);

            if(!$user) {
                return new JsonResponse(['error'=>'User not found'], Response::HTTP_NOT_FOUND);    
            }

            $this->manager->remove($user);
            $this->manager->flush();
        }
        catch(\Exception $ex) 
        {
            return new JsonResponse(['error' => 'Internal error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse([], Response::HTTP_NO_CONTENT);        
    }
    

    /**
     * @Route("/users/{id}", methods={"PUT"})
     */
    public function update(Request $request, int $id) {
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
    }

    /**
     * @Route("/users", methods={"POST"})
     */
    public function create(Request $request): Response 
    {
        $data = json_decode($request->getContent(), true);

        $user = new User($data['name'], $data['email']);

        foreach($data['telephones'] as $telephone) 
        {
            $user->addTelephone($telephone['number']);
        }
        
        $user->setCreatedDate(new \DateTime());

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
            return new JsonResponse(['error' => "Internal error " .  $ex->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $location = $request->getUriForPath("/users/" . $user->getId());
        return new JsonResponse($this->serializeUser($user), Response::HTTP_CREATED, [            
            "Location" => $location
        ]);
    }

    /**
     * @Route("/users", methods={"GET"})
     */
    public function index(): Response 
    {
        $users = $this->manager->getRepository(User::class)->findAll();
        $data = [];

        foreach($users as $user)
        {
            $data[] = $this->serializeUser($user);
        }

        return new JsonResponse($data, Response::HTTP_OK);
    } 

    /**
     * @Route("/users/{id}")
     */
    public function get(int $id): Response 
    {
        $user = $this->manager->getRepository(User::class)->findOneBy(['id'=>$id]);

        if ($user) {
            $serializedUser = $this->serializeUser($user);

            return new JsonResponse($serializedUser);
        } else {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
    }    
}