<?php

namespace App\Service;

use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\User;
use App\Error\AppError;

class ValidateUserService {
    private EntityManagerInterface $manager;
    private ValidatorInterface $validator;

    public function __construct(EntityManagerInterface $manager, 
        ValidatorInterface $validator)
    {
        $this->manager = $manager;
        $this->validator = $validator;
    }

    private function validateTelephones(User $user): void {
        $violations = [];

        foreach ($user->getTelephones() as $telephone)
        {
            
            $number = $telephone->getNumber();
            preg_replace("/\D/", "", $number);
            $length = strlen($number);            
                   
            if($length < 8 && $length > 15 ) {
                $violations[] = [
                    'property' => 'telephones[]',
                    'message' => 'Telephone must have number between 8 and 15 characteres.'
                ];
            }            
        }

        if (count($violations) > 0) {
            throw new AppError('Invalid User validation.', 400, $violations);
        }
    }

    public function execute(User $userNotValid): User 
    {       
       
        $errors = $this->validator->validate($userNotValid);
        
        if (count($errors) > 0) {
            $violations = array_map(fn(ConstraintViolationInterface $violation) => [
                'property' => $violation->getPropertyPath(),
                'message' => $violation->getMessage()
            ]
            ,iterator_to_array($errors));

            throw new AppError('Invalid User validation.', 400, $violations);
        }      
        
        $this->validateTelephones($userNotValid);

        $userWithEmailExists = 
            $this->manager->getRepository(User::class)
            ->findOneBy(['email' => strtolower($userNotValid->getEmail())]);

        if ($userWithEmailExists && $userWithEmailExists->getId() !== $userNotValid->getId()) {
            throw new AppError("Email already exists.", 400);
        }
        
        return $userNotValid;
    }
}
    
