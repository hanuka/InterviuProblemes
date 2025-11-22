<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'reservation')]
#[ORM\Index(name: 'idx_res_resource_time', columns: ['resource_id', 'start_at', 'end_at'])]
class Reservation
{
    public const STATUS_HELD = 'HELD';
    public const STATUS_CONFIRMED = 'CONFIRMED';
    public const STATUS_CANCELED = 'CANCELED';
    public const STATUS_EXPIRED = 'EXPIRED';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Resource::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    public Resource $resource;

    #[ORM\Column(type: 'datetime_immutable')]
    public \DateTimeImmutable $startAt;

    #[ORM\Column(type: 'datetime_immutable')]
    public \DateTimeImmutable $endAt;

    #[ORM\Column(length: 20)]
    public string $status;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    public ?\DateTimeImmutable $holdExpiresAt = null;

    #[ORM\Column(type: 'datetime_immutable')]
    public readonly \DateTimeImmutable $createdAt;

    public function __construct(
        Resource $resource,
        \DateTimeImmutable $startAt,
        \DateTimeImmutable $endAt,
        ?\DateTimeImmutable $holdExpiresAt = null
    ) {
        if ($endAt <= $startAt) {
            throw new \InvalidArgumentException('endAt must be after startAt');
        }

        $this->resource = $resource;
        $this->startAt = $startAt;
        $this->endAt = $endAt;
        $this->holdExpiresAt = $holdExpiresAt;
        $this->createdAt = new \DateTimeImmutable();
        $this->status = self::STATUS_HELD;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function isActiveHold(\DateTimeImmutable $now): bool
    {
        return $this->status === self::STATUS_HELD
            && $this->holdExpiresAt !== null
            && $this->holdExpiresAt > $now;
    }

    public function overlaps(\DateTimeImmutable $start, \DateTimeImmutable $end): bool
    {
        return !($this->endAt <= $start || $this->startAt >= $end);
    }
}