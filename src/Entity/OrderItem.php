<?php

namespace App\Entity;

use App\Repository\OrderItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderItemRepository::class)]
class OrderItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orderItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $OrderID = null;

    #[ORM\ManyToOne(inversedBy: 'orderItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $Item = null;

    #[ORM\Column]
    private ?int $Quantity = null;

    #[ORM\Column]
    private ?float $Price = null;

    public function __toString(){
        return $this->OrderID;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderID(): ?Order
    {
        return $this->OrderID;
    }

    public function setOrderID(?Order $OrderID): self
    {
        $this->OrderID = $OrderID;

        return $this;
    }

    public function getItem(): ?Product
    {
        return $this->Item;
    }

    public function setItem(?Product $Item): self
    {
        $this->Item = $Item;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->Quantity;
    }

    public function setQuantity(int $Quantity): self
    {
        $this->Quantity = $Quantity;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->Price;
    }

    public function setPrice(float $Price): self
    {
        $this->Price = $Price;

        return $this;
    }
}
