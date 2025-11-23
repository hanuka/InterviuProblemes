<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ConfirmReservationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use DomainException;
use DateTimeImmutable;

final class ReservationConfirmController extends AbstractController
{
    public function __construct(
        private ConfirmReservationService $confirmService
    ) {}

    #[Route('/api/reservations/{id}/confirm', methods: ['POST'])]
    public function confirm(int $id): JsonResponse
    {
        try {
            $reservation = $this->confirmService->confirm($id);
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