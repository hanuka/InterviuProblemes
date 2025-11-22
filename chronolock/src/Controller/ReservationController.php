<?php

declare(strict_types=1);

namespace App\Controller;

use App\Domain\Service\HoldReservationService;
use App\Repository\ResourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use DomainException;
use DateTimeImmutable;

final class ReservationController extends AbstractController
{
    public function __construct(
        private ResourceRepository $resourceRepository,
        private HoldReservationService $holdService
    ) {}

    #[Route('/api/reservations/hold', methods: ['POST'])]
    public function hold(Request $request): JsonResponse
    {
        $data = json_decode((string) $request->getContent(), true);

        if (!is_array($data)) {
            return new JsonResponse(['error' => 'Invalid JSON'], 400);
        }

        if (!isset($data['resourceId'], $data['startAt'], $data['endAt'])) {
            return new JsonResponse(['error' => 'Missing required fields'], 422);
        }

        $resource = $this->resourceRepository->find($data['resourceId']);
        if (!$resource) {
            return new JsonResponse(['error' => 'Resource not found'], 404);
        }

        try {
            $startAt = new DateTimeImmutable($data['startAt']);
            $endAt   = new DateTimeImmutable($data['endAt']);
        } catch (\Exception) {
            return new JsonResponse(['error' => 'Invalid date format'], 422);
        }

        if ($endAt <= $startAt) {
            return new JsonResponse(['error' => 'End date must be after start date'], 422);
        }

        try {
            $reservation = $this->holdService->hold($resource, $startAt, $endAt);
        } catch (DomainException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 409);
        }

        return new JsonResponse([
            'id'            => $reservation->id(),
            'resourceId'    => $reservation->resource->id(),
            'startAt'       => $reservation->startAt->format(DateTimeImmutable::ATOM),
            'endAt'         => $reservation->endAt->format(DateTimeImmutable::ATOM),
            'status'        => $reservation->status,
            'holdExpiresAt' => $reservation->holdExpiresAt?->format(DateTimeImmutable::ATOM),
        ], 201);
    }
}
