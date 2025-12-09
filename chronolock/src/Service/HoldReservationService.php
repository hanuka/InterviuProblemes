<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Reservation;
use App\Entity\Resource;
use App\Repository\ReservationRepository;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;
use Symfony\Component\Lock\Exception\LockConflictedException;
use Symfony\Component\Lock\LockFactory;

final class HoldReservationService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ReservationRepository $reservationRepository,
        private int $holdTtlSeconds,
        private LockFactory $chronolockResourceFactory,
    ) {
    }

    public function hold(Resource $resource, DateTimeImmutable $startAt, DateTimeImmutable $endAt): Reservation
    {
        $lockKey = sprintf('resource_lock_%d', $resource->getId());
        $lock = $this->chronolockResourceFactory->createLock($lockKey, 5.0);

        if (!$lock->acquire()) {
            throw new LockConflictedException(sprintf('Resource %d is locked', $resource->getId()));
        }

        try {
            $now = new DateTimeImmutable('now', new DateTimeZone('UTC'));

            if (
                $this->reservationRepository
                    ->hasOverlap($resource, $startAt, $endAt, $now)
            ) {
                throw new DomainException('Reservation overlaps with an existing one.');
            }

            $holdExpiresAt = $now->modify(sprintf('+%d seconds', $this->holdTtlSeconds));

            $reservation = new Reservation(
                $resource,
                $startAt,
                $endAt,
                $holdExpiresAt
            );

            $reservation->setStatus(Reservation::STATUS_HELD);

            $this->entityManager->persist($reservation);
            $this->entityManager->flush();

            return $reservation;
        } finally {
            $lock->release();
        }
    }
}
