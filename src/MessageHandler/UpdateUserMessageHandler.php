<?php

namespace App\MessageHandler;

use App\Message\UpdateUserMessage;
use App\Message\GetUserMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

use Symfony\Component\Validator\Validator\ValidatorInterface;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\User;
use App\Error\AppError;

use App\Service\ValidateUserService;

use App\Service\GetObjectsFromWrapper;
use GetObjectsFromWrapper as GlobalGetObjectsFromWrapper;

final class UpdateUserMessageHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $manager;
    private ValidatorInterface $validator;
    private MessageBusInterface $bus;

    public function __construct(EntityManagerInterface $manager, 
        ValidatorInterface $validator,
        MessageBusInterface $bus)
    {
        $this->manager = $manager;
        $this->validator = $validator;
        $this->bus = $bus;
    }    
    public function __invoke(UpdateUserMessage $message): User
    {
        $validatedUser = (new ValidateUserService($this->manager, $this->validator))->execute($message->getUser());

        $id = ($validatedUser->getId() !== null? $validatedUser->getId(): $message->getId());

        if(is_null($id)) {
            throw new AppError('Id is not valid.', 400);
        }

        $user = GlobalGetObjectsFromWrapper::execute($this->bus->dispatch(new GetUserMessage($id)));      

        $user->setName($validatedUser->getName());
        $user->setEmail($validatedUser->getEmail());
        $user->clearTelephones();

        foreach($validatedUser->getTelephones() as $telephone) 
        {
            $user->addTelephone($telephone->getNumber());
        }          

        try 
        {            
            $this->manager->beginTransaction();                 
            $this->manager->persist($user);          
            $this->manager->flush();
            $this->manager->commit();
            
        }
        catch(\Exception $ex) 
        {
            $this->manager->rollback();
            throw new AppError("Error Processing Request: " . $ex->getMessage(), 500);            
        }

        return $user;  

    }
}
