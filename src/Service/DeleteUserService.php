<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

use App\Error\AppError;
use App\Entity\User;

class DeleteUserService {
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function execute($userId): void 
    {
        try 
        {            
            $user = $this->manager->getRepository(User::class)->find($userId);

            if(!$user) {
                throw new AppError('User not found.', Response::HTTP_NOT_FOUND);    
            }

            $this->manager->remove($user);
            $this->manager->flush();
        }
        catch(\Exception $ex) 
        {
            throw new AppError('Internal error exception.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }        
    }

}