<?php


declare(strict_types=1);

namespace App\Service;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;

final class CancelReservationService
{
    public function __construct(
        private EntityManagerInterface $em,
        private ReservationRepository $reservations
    ) {}

    /**
     *
     * @throws DomainException
     */
    public function cancel(int $reservationId): Reservation
    {
        return $this->em->wrapInTransaction(function () use ($reservationId) {

            /** @var Reservation|null $reservation */
            $reservation = $this->reservations->find($reservationId);

            if (!$reservation) {
                throw new DomainException('Reservation not found');
            }

            if (in_array($reservation->status, [
                Reservation::STATUS_CANCELED,
                Reservation::STATUS_EXPIRED,
            ], true)) {
                throw new DomainException('Reservation cannot be canceled');
            }

            $reservation->status = Reservation::STATUS_CANCELED;

            $this->em->persist($reservation);
            $this->em->flush();

            return $reservation;
        });
    }
}
