<?php

namespace App\Domain\ValueObject;

class Address
{
    private string $street;
    private string $city;
    private string $postalCode;
    private string $phoneNumber;

    public function __construct(string $street, string $city, string $postalCode, string $phoneNumber)
    {
        $this->street = $street;
        $this->city = $city;
        $this->postalCode = $postalCode;
        $this->phoneNumber = $phoneNumber;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function __toArray(): array
    {
        return [
            'street' => $this->getStreet(),
            'city' => $this->getCity(),
            'postalCode' => $this->getPostalCode(),
            'phoneNumber' => $this->getPhoneNumber()
        ];
    }
}
