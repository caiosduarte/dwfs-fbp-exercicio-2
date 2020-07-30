<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

use App\Entity\User;



/**
 * @ORM\Entity
 */
class Telephone 
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;
    
    /** 
     * @ORM\ManyToOne(targetEntity="User", inversedBy="telephones")
     */
    private User $user;

    /**
     * @ORM\Column()
     * @Assert\Length(min="8", max="15", normalizer="trim")  
     * @Assert\Regex(pattern="/\+?\d{8,14}/", match="true", normalizer="trim")     
     */
    private string $number;

    public function __construct($number, $user)
    {
        $this->number = preg_replace("/\D/", "", $number);
        $this->user = $user;
    }

    public function getNumber(): string 
    {
        return $this->number;
    }

    public function getString() 
    {
        return $this->number;
    }
}