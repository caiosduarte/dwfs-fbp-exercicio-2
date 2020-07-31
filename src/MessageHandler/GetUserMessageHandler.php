<?php

namespace App\MessageHandler;

use App\Message\UserMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

use Doctrine\ORM\EntityManagerInterface;

use App\Error\AppError;
use App\Entity\User;

final class GetUserMessageHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function __invoke(UserMessage $message): User 
    {
        $user = $this->manager->getRepository(User::class)->find($message->getId());

        if(!$user) {
            throw new AppError('User not found.', 404);    
        }

        return $user;
    }
}
