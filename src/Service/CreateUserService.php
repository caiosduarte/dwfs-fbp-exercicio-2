<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Error\AppError;


class CreateUserService {
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function execute($userFromRequest): User 
    {
        
        $validateUserService = new ValidateUserService($this->manager);
        $validatedUser = $validateUserService->execute($userFromRequest);

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