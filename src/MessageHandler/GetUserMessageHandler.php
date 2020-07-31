<?php

namespace App\MessageHandler;



use App\Message\GetUserMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

use Doctrine\ORM\EntityManagerInterface;

use App\Error\AppError;
use App\Entity\User;

use App\Service\GetObjectsFromWrapper;
use GetObjectsFromWrapper as GlobalGetObjectsFromWrapper;

final class GetUserMessageHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function __invoke(GetUserMessage $message): User 
    {
        $user = $this->manager->getRepository(User::class)->find($message->getId());

        
        if(is_null($user)) {
            throw new AppError('User not found.', 404);    
        }

        return $user;
    }
}
