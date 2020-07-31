<?php

namespace App\MessageHandler;

use App\Message\GetUserMessage;
use App\Message\RemoveUserMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;

final class RemoveUserMessageHandler implements MessageHandlerInterface
{
    protected EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function __invoke(RemoveUserMessage $message)
    {
        $user = $this->bus->dispatch(new GetUserMessage($message->getId()));
        
        $this->manager->remove($user);
        $this->manager->flush();
    }
}
