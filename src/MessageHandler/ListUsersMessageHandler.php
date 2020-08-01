<?php

namespace App\MessageHandler;

use App\Message\ListUsersMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\User;


final class ListUsersMessageHandler implements MessageHandlerInterface
{
    protected EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }  

    public function __invoke(ListUsersMessage $message): ?array
    {
        return $this->manager->getRepository(User::class)->findAll();
    }
}
