<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ConfirmReservationService;
use DateTimeInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Lock\Exception\LockConflictedException;
use Symfony\Component\Routing\Annotation\Route;
use DomainException;

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
        } catch (LockConflictedException) {
            return new JsonResponse(['error' => 'Resource is locked, please try again.'], 409);
        } catch (DomainException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 409);
        }

        return new JsonResponse([
            'id'            => $reservation->id(),
            'resourceId'    => $reservation->resource->id(),
            'startAt'       => $reservation->startAt->format(DateTimeInterface::ATOM),
            'endAt'         => $reservation->endAt->format(DateTimeInterface::ATOM),
            'status'        => $reservation->status,
            'holdExpiresAt' => $reservation->holdExpiresAt?->format(DateTimeInterface::ATOM),
        ]);
    }
}