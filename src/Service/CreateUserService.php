<?php

namespace App\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Error\AppError;

class CreateUserService {
    private EntityManagerInterface $manager;
    private ValidatorInterface $validator;

    public function __construct(EntityManagerInterface $manager, 
        ValidatorInterface $validator)
    {
        $this->manager = $manager;
        $this->validator = $validator;
    }

    public function execute($userFromRequest): User 
    {
        $validatedUser = (new ValidateUserService($this->manager, $this->validator))->execute($userFromRequest);

        $validatedUser->setCreatedDate(new \DateTime());

        try 
        {            
            $this->manager->beginTransaction();                 
            $this->manager->persist($validatedUser);          
            $this->manager->flush();
            $this->manager->commit();
        }
        catch(\Exception $ex) 
        {
            $this->manager->rollback();
            throw new AppError("Error Processing Request: " . $ex->getMessage(), 500);            
        }

        return $validatedUser;  
    }
}