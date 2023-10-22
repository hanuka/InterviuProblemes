<?php

namespace App\Domain\ValueObject;

class Company
{
    private string $name;
    private Address $address;

    public function __construct(string $name, Address $address)
    {
        $this->name = $name;
        $this->address = $address;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName()
        ];
    }
}
