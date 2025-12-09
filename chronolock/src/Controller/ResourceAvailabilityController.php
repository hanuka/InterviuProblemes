<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ResourceRepository;
use App\Service\GetAvailabilityService;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class ResourceAvailabilityController extends AbstractController
{
    public function __construct(
        private ResourceRepository $resources,
        private GetAvailabilityService $availabilityService
    ) {}

    #[Route('/api/resources/{id}/availability', methods: ['GET'])]
    public function availability(int $id, Request $request): JsonResponse
    {
        $resource = $this->resources->find($id);
        if (!$resource) {
            return new JsonResponse(['error' => 'Resource not found'], 404);
        }

        $fromRaw = $request->query->get('from');
        $toRaw   = $request->query->get('to');

        if (!$fromRaw || !$toRaw) {
            return new JsonResponse(['error' => 'from and to query params are required'], 422);
        }

        try {
            $from = new DateTimeImmutable($fromRaw);
            $to   = new DateTimeImmutable($toRaw);
        } catch (\Exception) {
            return new JsonResponse(['error' => 'Invalid date format'], 422);
        }

        if ($to <= $from) {
            return new JsonResponse(['error' => 'to must be after from'], 422);
        }

        $freeIntervals = $this->availabilityService->getAvailability($resource, $from, $to);

        $payload = array_map(static function (array $i) {
            return [
                'startAt' => $i['startAt']->format(DateTimeImmutable::ATOM),
                'endAt'   => $i['endAt']->format(DateTimeImmutable::ATOM),
            ];
        }, $freeIntervals);

        return new JsonResponse([
            'resourceId' => $resource->id(),
            'from'       => $from->format(DateTimeImmutable::ATOM),
            'to'         => $to->format(DateTimeImmutable::ATOM),
            'free'       => $payload,
        ]);
    }
}
