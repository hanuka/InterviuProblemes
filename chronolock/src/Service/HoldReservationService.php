<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Reservation;
use App\Entity\Resource;
use App\Repository\ReservationRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;

final class HoldReservationService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ReservationRepository $reservationRepository,
        private int $holdTtlSeconds
    ) {
    }

    public function hold(Resource $resource, DateTimeImmutable $startAt, DateTimeImmutable $endAt): Reservation
    {
        $now = new DateTimeImmutable();

        return $this->entityManager->wrapInTransaction(function () use ($resource, $startAt, $endAt, $now) {
            if ($this->reservationRepository->hasOverlap($resource, $startAt, $endAt, $now)) {
                throw new DomainException('Reservation overlaps with an existing one.');
            }

            $holdExpiresAt = $now->modify("+{$this->holdTtlSeconds} seconds");

            $reservation = new Reservation(
                $resource,
                $startAt,
                $endAt,
                $holdExpiresAt
            );

            $this->entityManager->persist($reservation);
            $this->entityManager->flush();

            return $reservation;
        });
    }
}
