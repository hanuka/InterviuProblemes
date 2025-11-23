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
}