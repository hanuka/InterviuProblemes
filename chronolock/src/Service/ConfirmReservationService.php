<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;
use DateTimeImmutable;

final class ConfirmReservationService
{
    public function __construct(
        private EntityManagerInterface $em,
        private ReservationRepository $reservations
    ) {}

    /**
     * @throws DomainException
     */
    public function confirm(int $reservationId): Reservation
    {
        $now = new DateTimeImmutable();

        return $this->em->wrapInTransaction(function () use ($reservationId, $now) {

            /** @var Reservation|null $reservation */
            $reservation = $this->reservations->find($reservationId);

            if (!$reservation) {
                throw new DomainException('Reservation not found');
            }

            if ($reservation->status !== Reservation::STATUS_HELD) {
                throw new DomainException('Only HELD reservations can be confirmed');
            }

            if ($reservation->holdExpiresAt === null || $reservation->holdExpiresAt <= $now) {
                throw new DomainException('Hold has expired');
            }

            if ($this->reservations->hasOverlap(
                $reservation->resource,
                $reservation->startAt,
                $reservation->endAt,
                $now,
                $reservation->id()
            )) {
                throw new DomainException('Reservation overlaps and cannot be confirmed');
            }

            $reservation->status = Reservation::STATUS_CONFIRMED;

            $this->em->persist($reservation);
            $this->em->flush();

            return $reservation;
        });
    }
}