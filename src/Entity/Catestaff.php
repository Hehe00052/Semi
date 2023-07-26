<?php

namespace App\Entity;

use App\Repository\CatestaffRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CatestaffRepository::class)]
class Catestaff
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $Name = null;

    #[ORM\OneToMany(mappedBy: 'staffcate', targetEntity: Staffs::class)]
    private Collection $staffs;

    public function __construct()
    {
        $this->staffs = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Staffs>
     */
    public function getStaffs(): Collection
    {
        return $this->staffs;
    }

    public function addStaff(Staffs $staff): static
    {
        if (!$this->staffs->contains($staff)) {
            $this->staffs->add($staff);
            $staff->setStaffcate($this);
        }

        return $this;
    }

    public function removeStaff(Staffs $staff): static
    {
        if ($this->staffs->removeElement($staff)) {
            // set the owning side to null (unless already changed)
            if ($staff->getStaffcate() === $this) {
                $staff->setStaffcate(null);
            }
        }

        return $this;
    }
}
