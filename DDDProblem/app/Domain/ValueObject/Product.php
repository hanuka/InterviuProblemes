<?php

namespace App\Domain\ValueObject;

class Product
{
    private string $name;
    private int $quantity;
    private float $unitPrice;

    public function __construct(string $name, int $quantity, float $unitPrice)
    {
        $this->name = $name;
        $this->quantity = $quantity;
        $this->unitPrice = $unitPrice;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getUnitPrice(): float
    {
        return $this->unitPrice;
    }

    public function getTotal(): float
    {
        return $this->quantity * $this->unitPrice;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName()
        ];
    }
}
