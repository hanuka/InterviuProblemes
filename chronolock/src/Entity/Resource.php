<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'resource')]
class Resource
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 120)]
    public string $name;

    #[ORM\Column]
    public readonly \DateTimeImmutable $createdAt;

    #[ORM\OneToMany(mappedBy: 'resource', targetEntity: Reservation::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $reservations;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->createdAt = new \DateTimeImmutable();
        $this->reservations = new ArrayCollection();
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function reservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): void
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->resource = $this;
        }
    }

    public function getId()
    {
        return $this->id;
    }
}