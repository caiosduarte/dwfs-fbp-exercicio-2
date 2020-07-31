<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

use App\Error\AppError;
use App\Entity\User;

class GetUserService {
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function execute($userId): User 
    {
        $user = $this->manager->getRepository(User::class)->find($userId);

        if(!$user) {
            throw new AppError('User not found.', 404);    
        }

        return $user;
    }

}