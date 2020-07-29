<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

use App\Error\AppError;

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
            throw new AppError('Internal error exception.', 500);
        }        
    }

}