<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Customer_name = null;

    #[ORM\Column(length: 255)]
    private ?string $Customer_address = null;

    #[ORM\Column(length: 13)]
    private ?string $Customer_phone = null;

    #[ORM\Column]
    private ?float $Total_price = null;

    #[ORM\Column]
    private ?bool $Status = null;

    #[ORM\OneToMany(mappedBy: 'OrderID', targetEntity: OrderItem::class)]
    private Collection $orderItems;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $DateOut = null;

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
    }

    public function __toString(){
        return $this->id;
        return $this->date;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomerName(): ?string
    {
        return $this->Customer_name;
    }

    public function setCustomerName(string $Customer_name): self
    {
        $this->Customer_name = $Customer_name;

        return $this;
    }

    public function getCustomerAddress(): ?string
    {
        return $this->Customer_address;
    }

    public function setCustomerAddress(string $Customer_address): self
    {
        $this->Customer_address = $Customer_address;

        return $this;
    }

    public function getCustomerPhone(): ?string
    {
        return $this->Customer_phone;
    }

    public function setCustomerPhone(string $Customer_phone): self
    {
        $this->Customer_phone = $Customer_phone;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->Total_price;
    }

    public function setTotalPrice(float $Total_price): self
    {
        $this->Total_price = $Total_price;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->Status;
    }

    public function setStatus(bool $Status): self
    {
        $this->Status = $Status;

        return $this;
    }

    /**
     * @return Collection<int, OrderItem>
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $orderItem): self
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems->add($orderItem);
            $orderItem->setOrderID($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): self
    {
        if ($this->orderItems->removeElement($orderItem)) {
            // set the owning side to null (unless already changed)
            if ($orderItem->getOrderID() === $this) {
                $orderItem->setOrderID(null);
            }
        }

        return $this;
    }

    public function getDateOut(): ?\DateTimeInterface
    {
        return $this->DateOut;
    }

    public function setDateOut(?\DateTimeInterface $DateOut): static
    {
        $this->DateOut = $DateOut;

        return $this;
    }
}
