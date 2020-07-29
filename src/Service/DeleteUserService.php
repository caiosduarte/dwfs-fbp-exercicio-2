<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class DeleteUserService {
    protected EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function execute($userId): void 
    {
        $getUserService = new GetUserService($this->manager);
        $user = $getUserService->execute($userId); 
        
        $this->manager->remove($user);
        $this->manager->flush();
    }
}