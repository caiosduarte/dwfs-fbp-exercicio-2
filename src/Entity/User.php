<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class User 
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     */
    private ?int $id = null;
    
    /**
     * @ORM\Column()
     * @Assert\NotBlank(normalizer="trim")     
     */
    private ?string $name = null;

    /**
     * @ORM\Column()
     * @Assert\NotBlank(normalizer="trim")  
     * @Assert\Email()
     */
    private ?string $email = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTime $createdDate = null;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Telephone", mappedBy="user", orphanRemoval=true, cascade={"persist", "remove", "refresh", "detach"})
     * @Assert\Count(min="1", max="10")          
     * @Assert\Valid()
     */
    private Collection $telephones;

    public function __construct($name, $email)
    {
        $this->name = $name;
        $this->email = $email;
        $this->telephones = new ArrayCollection();
    }

    public function addTelephone($number) 
    {
        $this->telephones[] = new Telephone($number, $this);
    }

    public function clearTelephones(): void
    {
        $this->telephones->clear();
    }    

    public function getTelephones() 
    {
        return $this->telephones;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail($email) 
    {
        $this->email = $email;
    }

    public function getCreatedDate() 
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTime $createdDate): void
    {
        $this->createdDate = $createdDate;
    }

}