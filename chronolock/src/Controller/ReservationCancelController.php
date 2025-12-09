<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\CancelReservationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Lock\Exception\LockConflictedException;
use Symfony\Component\Routing\Annotation\Route;
use DomainException;
use DateTimeImmutable;

final class ReservationCancelController extends AbstractController
{
    public function __construct(
        private CancelReservationService $cancelService
    ) {}

    #[Route('/api/reservations/{id}/cancel', methods: ['POST'])]
    public function cancel(int $id): JsonResponse
    {
        try {
            $reservation = $this->cancelService->cancel($id);
        } catch (LockConflictedException) {
            return new JsonResponse(['error' => 'Resource is locked, please try again.'], 409);
        } catch (DomainException $e) {
            if ($e->getMessage() === 'Reservation not found') {
                return new JsonResponse(['error' => $e->getMessage()], 404);
            }

            return new JsonResponse(['error' => $e->getMessage()], 409);
        }

        return new JsonResponse([
            'id'            => $reservation->id(),
            'resourceId'    => $reservation->resource->id(),
            'startAt'       => $reservation->startAt->format(DateTimeImmutable::ATOM),
            'endAt'         => $reservation->endAt->format(DateTimeImmutable::ATOM),
            'status'        => $reservation->status,
            'holdExpiresAt' => $reservation->holdExpiresAt?->format(DateTimeImmutable::ATOM),
        ]);
    }
}