<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Asst;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $adress;

    #[Asst\Regex(
        pattern: '^[0-9]+$^',
        htmlPattern: '^[0-9]+$^',
        message: 'Le code postal ne doit contenir que des chiffres',
    )]
    #[ORM\Column(type: 'integer')]
    private $zipcode;

    #[ORM\Column(type: 'string', length: 255)]
    private $city;

    #[Asst\Regex(
        pattern: '^[0-9]+$^',
        htmlPattern: '^[0-9]+$^',
        message: 'Le numero doit contenir uniquement des chiffres',
    )]
    #[ORM\Column(type: 'string', length: 255)]
    private $phone;

    #[Asst\Regex(
        pattern: "/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/ ",
        htmlPattern:'^[a-zA-Z0-9._-]@[a-zA-Z0-9.-_]{2,}\.[a-zA-Z0-9.-]{2,4}+$^',
        message: 'Adresse mail incorrect',
    )]
    #[ORM\Column(type: 'string', length: 150, nullable: true)]
    private $email;

    #[ORM\Column(type: 'text', nullable: true)]
    private $text;

 
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getZipcode(): ?int
    {
        return $this->zipcode;
    }

    public function setZipcode(int $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }


}
