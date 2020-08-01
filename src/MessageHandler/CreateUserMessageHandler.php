<?php

namespace App\MessageHandler;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

use Symfony\Component\Validator\Validator\ValidatorInterface;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\User;
use App\Message\CreateUserMessage;
use App\Error\AppError;

use App\Service\ValidateUserService;

final class CreateUserMessageHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $manager;
    private ValidatorInterface $validator;

    public function __construct(EntityManagerInterface $manager, 
        ValidatorInterface $validator)
    {
        $this->manager = $manager;
        $this->validator = $validator;
    }

    public function __invoke(CreateUserMessage $message): User 
    {
        $validatedUser = (new ValidateUserService($this->manager, $this->validator))->execute($message->getUser());

        $validatedUser->setCreatedDate(new \DateTime());

        try 
        {            
            $this->manager->beginTransaction();                 
            $this->manager->persist($validatedUser);          
            $this->manager->flush();
            $this->manager->commit();
        }
        catch(\Exception $ex) 
        {
            $this->manager->rollback();
            throw new AppError("Error Processing Request: " . $ex->getMessage(), 500);            
        }

        return $validatedUser;  
    }
}
