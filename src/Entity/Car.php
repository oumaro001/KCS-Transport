<?php

namespace App\Entity;


use Serializable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CarRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Asst;

#[Vich\Uploadable] 
#[ORM\Entity(repositoryClass: CarRepository::class)]

class Car implements \Serializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $brand;

    #[ORM\Column(type: 'string', length: 255)]
    private $model;

    #[Asst\Regex(
        pattern: '^[a-zA-Z0-9-()]+$^',
        htmlPattern: '^[a-zA-Z0-9-()]+$^',
        message: 'Le numero d\'immatriculation est incorrect',
    )]
    #[ORM\Column(type: 'string', length: 50)]
    private $register;

    
    #[ORM\OneToMany(mappedBy: 'car', targetEntity: User::class,)]
    private $users;

    #[Vich\UploadableField(mapping: 'cars_images', fileNameProperty: 'imageName',)]
    private ?File $imageFile = null;

    #[ORM\Column(type: 'string',nullable:true)]
    private ?string $imageName = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $updated_at;

    #[ORM\Column(type: 'boolean')]
    private $ramp;

    #[ORM\Column(type: 'decimal', precision: '0', scale: '0', nullable: true)]
    private $places;

   

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getRegister(): ?string
    {
        return $this->register;
    }

    public function setRegister(string $register): self
    {
        $this->register = $register;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setCar($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCar() === $this) {
                $user->setCar(null);
            }
        }

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            //Il est nécessaire qu'au moins un champ change si vous utilisez la doctrine
             // sinon les écouteurs d'événements ne seront pas appelés et le fichier sera perdu
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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getRamp(): ?bool
    {
        return $this->ramp;
    }

    public function setRamp(bool $ramp): self
    {
        $this->ramp = $ramp;

        return $this;
    }


    public function __toString()
    {
        return $this->register;
    }

    public function getPlaces(): ?string
    {
        return $this->places;
    }

    public function setPlaces(?string $places): self
    {
        $this->places = $places;

        return $this;
    }

    public function serialize()
    {
        return serialize(array(
          
            $this->id,
            $this->imageName,
            // see section on salt below
            // $this->salt,
        ));
    }
    
    public function unserialize($serialized)
    {
        list (
            
            $this->id,
            $this->imageName,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }
    
}
