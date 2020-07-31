<?php

namespace App\MessageHandler;

use App\Message\GetUserMessage;
use App\Message\RemoveUserMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Doctrine\ORM\EntityManagerInterface;

use App\Service\GetObjectsFromWrapper;
use GetObjectsFromWrapper as GlobalGetObjectsFromWrapper;


final class RemoveUserMessageHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $manager;
    private MessageBusInterface $bus;

    public function __construct(EntityManagerInterface $manager,
    MessageBusInterface $bus)
    {
        $this->manager = $manager;
        $this->bus = $bus;
    }

    public function __invoke(RemoveUserMessage $message): void
    {
        $user = GlobalGetObjectsFromWrapper::execute($this->bus->dispatch(new GetUserMessage($message->getId())));

        $this->manager->remove($user);
        $this->manager->flush();
    }
}
