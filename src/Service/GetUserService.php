<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
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
            throw new AppError('User not found.', Response::HTTP_NOT_FOUND);    
        }

        return $user;
    }

}