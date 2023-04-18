<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["user:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 40)]
    #[Groups(["user:read"])]
    #[NotBlank]
    #[Regex("/^[a-zA-Z\u00C0-\u00FF]+$/")]
    private ?string $lastName = null;

    #[ORM\Column(length: 40)]
    #[Groups(["user:read"])]
    #[NotBlank]
    #[Regex("/^[a-zA-Z\u00C0-\u00FF]+$/")]
    private ?string $firstName = null;

    #[ORM\Column(length: 40)]
    #[Groups(["user:read"])]
    #[NotBlank]
    #[Email]
    private ?string $email = null;

    #[ORM\Column(length: 40)]
    #[Groups(["user:read"])]
    #[NotBlank]
    private ?string $adress = null;

    #[ORM\Column(length: 40, nullable: true)]
    #[Groups(["user:read"])]
    #[NotBlank]
    #[Regex("/^[0-9]{10}$/")]
    private ?string $phoneNumber = null;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Property::class, orphanRemoval: true)]
    private Collection $properties;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(["user:read"])]
    #[NotBlank]
    #[Date]
    private ?\DateTimeInterface $birthDate = null;

    public function __construct()
    {
        $this->properties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
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

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * @return Collection<int, Property>
     */
    public function getProperties(): Collection
    {
        return $this->properties;
    }

    public function addProperty(Property $property): self
    {
        if (!$this->properties->contains($property)) {
            $this->properties->add($property);
            $property->setOwner($this);
        }

        return $this;
    }

    public function removeProperty(Property $property): self
    {
        if ($this->properties->removeElement($property)) {
            // set the owning side to null (unless already changed)
            if ($property->getOwner() === $this) {
                $property->setOwner(null);
            }
        }

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getAgeString(){
        $now = new DateTimeImmutable();

        $age = $now->diff($this->getBirthDate(), true)->y;
        
        return $age > 1 ? $age . "ans" : $age . "an";
        
    }
}
