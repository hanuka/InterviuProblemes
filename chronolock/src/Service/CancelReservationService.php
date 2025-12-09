<?php


declare(strict_types=1);

namespace App\Service;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;
use Symfony\Component\Lock\Exception\LockConflictedException;
use Symfony\Component\Lock\LockFactory;

final class CancelReservationService
{
    public function __construct(
        private EntityManagerInterface $em,
        private ReservationRepository $reservations,
        private LockFactory $chronolockResourceFactory
    ) {}

    /**
     *
     * @throws DomainException
     */
    public function cancel(int $reservationId): Reservation
    {
        /** @var Reservation|null $reservation */
        $reservation = $this->reservations->find($reservationId);

        if (!$reservation) {
            throw new DomainException('Reservation not found');
        }

        $resource = $reservation->resource;

        $lockKey = sprintf('resource_lock_%d', $resource->id());
        $lock = $this->chronolockResourceFactory->createLock($lockKey, 5.0);

        if (!$lock->acquire()) {
            throw new LockConflictedException(sprintf('Resource %d is locked.', $resource->id()));
        }

        try {
            if (in_array($reservation->status, [
                Reservation::STATUS_CANCELED,
                Reservation::STATUS_EXPIRED,
            ], true)) {
                throw new DomainException('Reservation cannot be canceled');
            }

            $reservation->status = Reservation::STATUS_CANCELED;

            $this->em->flush();

            return $reservation;
        } finally {
            $lock->release();
        }
    }
}
