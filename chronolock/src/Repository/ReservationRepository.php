<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Reservation;
use App\Entity\Resource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function hasOverlap(
        Resource $resource,
        \DateTimeImmutable $start,
        \DateTimeImmutable $end,
        \DateTimeImmutable $now,
        ?int $excludeReservationId = null
    ): bool {
        $qb = $this->createQueryBuilder('r');

        $qb->select('COUNT(r.id)')
            ->andWhere('r.resource = :resource')
            ->andWhere('r.startAt < :end')
            ->andWhere('r.endAt > :start')
            ->andWhere(
                $qb->expr()->orX(
                    'r.status = :confirmed',
                    $qb->expr()->andX(
                        'r.status = :held',
                        'r.holdExpiresAt IS NOT NULL',
                        'r.holdExpiresAt > :now'
                    )
                )
            )
            ->setParameter('resource', $resource)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->setParameter('now', $now)
            ->setParameter('confirmed', Reservation::STATUS_CONFIRMED)
            ->setParameter('held', Reservation::STATUS_HELD);

        if ($excludeReservationId !== null) {
            $qb->andWhere('r.id != :excludeId')
                ->setParameter('excludeId', $excludeReservationId);
        }

        return ((int) $qb->getQuery()->getSingleScalarResult()) > 0;
    }

    public function expireHeldReservations(\DateTimeImmutable $now): int
    {
        return $this->createQueryBuilder('r')
            ->update()
            ->set('r.status', ':expired')
            ->where('r.status = :held')
            ->andWhere('r.holdExpiresAt IS NOT NULL')
            ->andWhere('r.holdExpiresAt <= :now')
            ->setParameter('expired', Reservation::STATUS_EXPIRED)
            ->setParameter('held', Reservation::STATUS_HELD)
            ->setParameter('now', $now)
            ->getQuery()
            ->execute();
    }

    public function findBusyIntervals(
        Resource $resource,
        \DateTimeImmutable $from,
        \DateTimeImmutable $to,
        \DateTimeImmutable $now
    ): array {
        $qb = $this->createQueryBuilder('r');

        return $qb
            ->andWhere('r.resource = :resource')
            ->andWhere('r.startAt < :to')
            ->andWhere('r.endAt > :from')
            ->andWhere(
                $qb->expr()->orX(
                    'r.status = :confirmed',
                    $qb->expr()->andX(
                        'r.status = :held',
                        'r.holdExpiresAt > :now'
                    )
                )
            )
            ->setParameter('resource', $resource)
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->setParameter('now', $now)
            ->setParameter('confirmed', Reservation::STATUS_CONFIRMED)
            ->setParameter('held', Reservation::STATUS_HELD)

            ->orderBy('r.startAt', 'ASC')
            ->getQuery()
            ->getResult();
    }
}