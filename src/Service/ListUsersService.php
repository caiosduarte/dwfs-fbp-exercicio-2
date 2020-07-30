<?php

namespace App\Service;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\User;

class ListUsersService 
{
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }
    
    public function execute()
    {
        return $this->manager->getRepository(User::class)->findAll();
    }
}
    
