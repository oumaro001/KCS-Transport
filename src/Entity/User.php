<?php

namespace App\Entity;


use Webmozart\Assert\Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Asst;
use Symfony\Component\Serializer\Annotation\Ignore;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
 
 

#[Vich\Uploadable] 
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{


   
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Asst\Regex(
        pattern: "/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/ ",
        htmlPattern:'^[a-zA-Z0-9._-]@[a-zA-Z0-9.-_]{2,}\.[a-zA-Z0-9._-]{2,4}+$^',
        message: 'Adresse mail incorrect',
    )]
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;
    
    #[Asst\Regex(
        pattern: '^[a-zA-Z]+$^',
        htmlPattern: '^[a-zA-Z]+$^',
        message: 'votre Nom ne peut pas contenir de chiffre',
    )]
    #[ORM\Column(type: 'string', length: 255)]
    private $lastname;

    #[Asst\Regex(
        pattern: '^[a-zA-Z]+$^',
        htmlPattern: '^[a-zA-Z]+$^',
        message: 'votre nom ne peut pas contenir de chiffre',
    )]
    #[ORM\Column(type: 'string', length: 255)]
    private $firstname;
    
    #[Asst\Regex(
        pattern: '^[a-zA-Z0-9-]+$^',
        htmlPattern: '^[a-zA-Z0-9-]+$^',
        message: 'Le nom de la fonction est incorrect',
    )]
    #[ORM\Column(type: 'string', length: 50)]
    private $fonction;

    #[Asst\Regex(
        pattern: '^[0-9]+$^',
        htmlPattern: '^[0-9]+$^',
        message: 'Le numero doit contenir uniquement des chiffres',
    )]
    #[ORM\Column(type: 'string', length: 50)]
    private $phone;

    #[ORM\Column(type: 'datetime_immutable',nullable:true)]
    private $updated_at;

    
    #[ORM\ManyToOne(targetEntity: Car::class, inversedBy: 'users',)]
    #[ORM\JoinColumn(onDelete:"SET NULL")]
    
    private $car;

    /**
     * 
     *
     * @Ignore()
     */
    #[Vich\UploadableField(mapping: 'users_images', fileNameProperty: 'imageName',)]
    private ?File $imageFile = null;

    #[ORM\Column(type: 'string',nullable:true)]
    private ?string $imageName = null;
    
   
    #[ORM\Column(type: 'string', length: 200, nullable: true)]
    private $adress;
    
    #[Asst\Regex(
        pattern: '^[0-9]+$^',
        htmlPattern: '^[0-9]+$^',
        message: 'Le code postal ne doit contenir que des chiffres',
    )]
    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private $zipcode;

  
    #[ORM\Column(type: 'string', length: 200, nullable: true)]
    private $city;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getFonction(): ?string
    {
        return $this->fonction;
    }

    public function setFonction(string $fonction): self
    {
        $this->fonction = $fonction;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): self
    {
        $this->car = $car;

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updated_at = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function serialize()
{
    return serialize(array(
        $this->id,
        $this->username,
        $this->password,
        // see section on salt below
        // $this->salt,
    ));
}

public function unserialize($serialized)
{
    list (
        $this->id,
        $this->username,
        $this->password,
        // see section on salt below
        // $this->salt
    ) = unserialize($serialized);
}

public function getAdress(): ?string
{
    return $this->adress;
}

public function setAdress(?string $adress): self
{
    $this->adress = $adress;

    return $this;
}

public function getZipcode(): ?string
{
    return $this->zipcode;
}

public function setZipcode(?string $zipcode): self
{
    $this->zipcode = $zipcode;

    return $this;
}

public function getCity(): ?string
{
    return $this->city;
}

public function setCity(?string $city): self
{
    $this->city = $city;

    return $this;
}


}
