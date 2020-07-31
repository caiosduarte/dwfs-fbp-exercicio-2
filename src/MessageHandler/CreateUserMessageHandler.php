<?php

namespace App\MessageHandler;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

use App\Message\CreateUserMessage;
use App\Error\AppError;

final class CreateUserMessageHandler implements MessageHandlerInterface
{
    public function __invoke(CreateUserMessage $message)
    {
        //$validatedUser = (new ValidateUserService($this->manager, $this->validator))->execute($userFromRequest);

        //$validatedUser->setCreatedDate(new \DateTime());
/*
        $validatedUser = $message->getUser();

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

        return $validatedUser;  */
    }
}
