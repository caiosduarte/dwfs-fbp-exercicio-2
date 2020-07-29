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
        $getUserService = new GetUserService($this->manager);

        $user = $getUserService->execute($userId); 

        try 
        {            
            $this->manager->remove($user);
            $this->manager->flush();
        }
        catch(\Exception $ex) 
        {
            throw new AppError('Internal error exception.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }        
    }

}