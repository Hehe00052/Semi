<?php

namespace App\Entity;

use App\Repository\StaffsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StaffsRepository::class)]
class Staffs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    #[ORM\Column]
    private ?float $Phonenumber = null;

    #[ORM\Column(length: 255)]
    private ?string $Photo = null;

    #[ORM\ManyToOne(inversedBy: 'staffs')]
    private ?Catestaff $staffcate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): static
    {
        $this->Name = $Name;

        return $this;
    }

    public function getPhonenumber(): ?float
    {
        return $this->Phonenumber;
    }

    public function setPhonenumber(float $Phonenumber): static
    {
        $this->Phonenumber = $Phonenumber;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->Photo;
    }

    public function setPhoto(string $Photo): static
    {
        $this->Photo = $Photo;

        return $this;
    }

    public function getStaffcate(): ?Catestaff
    {
        return $this->staffcate;
    }

    public function setStaffcate(?Catestaff $staffcate): static
    {
        $this->staffcate = $staffcate;

        return $this;
    }
} 

