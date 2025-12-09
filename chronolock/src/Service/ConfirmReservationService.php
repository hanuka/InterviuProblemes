<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;
use DateTimeImmutable;
use Symfony\Component\Lock\Exception\LockConflictedException;
use Symfony\Component\Lock\LockFactory;

final class ConfirmReservationService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ReservationRepository  $reservationRepository,
        private LockFactory $chronolockResourceFactory,
    ) {}

    /**
     * @throws DomainException
     */
    public function confirm(int $reservationId): Reservation
    {
        $now = new DateTimeImmutable('now');

        $reservation = $this->reservationRepository->find($reservationId);
        if (!$reservation instanceof Reservation) {
            throw new DomainException('Reservation not found');
        }

        $resource = $reservation->resource;

        $lockKey = sprintf('resource_lock_%d', $resource->id());
        $lock = $this->chronolockResourceFactory->createLock($lockKey, 5.0);

        if (!$lock->acquire()) {
            throw new LockConflictedException(sprintf('Resource %d is locked.', $resource->id()));
        }

        try {
            if ($reservation->status === Reservation::STATUS_EXPIRED) {
                throw new DomainException('Reservation expired');
            }

            if ($reservation->status === Reservation::STATUS_CANCELED) {
                throw new DomainException('Reservation canceled');
            }

            if ($reservation->status === Reservation::STATUS_CONFIRMED) {
                throw new DomainException('Reservation already confirmed');
            }

            if ($this->reservationRepository->hasOverlap(
                $resource,
                $reservation->startAt,
                $reservation->endAt,
                $now,
                $reservation->id(),
            )) {
                throw new DomainException('Reservation overlaps with an existing one.');
            }

            $reservation->status = Reservation::STATUS_CONFIRMED;
            $this->entityManager->flush();

            return $reservation;

        } finally {
            $lock->release();
        }
    }
}