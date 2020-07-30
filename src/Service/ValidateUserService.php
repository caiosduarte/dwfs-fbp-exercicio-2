<?php

namespace App\Service;

use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ValidatorBuilder;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Criteria;

use App\Entity\User;
use App\Error\AppError;

class ValidateUserService {
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function execute(User $userNotValid): User 
    {
        $validator = (new ValidatorBuilder())->getValidator();
        $errors = $validator->validate($userNotValid);
        
        if (count($errors) > 0) {
            $violations = array_map(fn(ConstraintViolationInterface $violation) => [
                'property' => $violation->getPropertyPath(),
                'message' => $violation->getMessage()
            ]
            ,iterator_to_array($errors));

            dump($violations);   
            //return new AppError($violations, 400);
        }       


         $userWithEmailExists = 
            $this->manager->getRepository(User::class)
            ->findOneBy(['email' => $userNotValid->getEmail()]);


        if ($userWithEmailExists && $userWithEmailExists->getId() !== $userNotValid->getId()) {
            throw new AppError("Email already exists.", 400);
        }
        
        return $userNotValid;
    }
}
    
