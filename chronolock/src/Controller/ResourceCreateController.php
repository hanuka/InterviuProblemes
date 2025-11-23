<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\CreateResourceService;
use DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class ResourceCreateController extends AbstractController
{
    public function __construct(
        private CreateResourceService $createResourceService
    ) {}

    #[Route('/api/resources', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode((string) $request->getContent(), true);

        if (!is_array($data)) {
            return new JsonResponse(['error' => 'Invalid JSON'], 400);
        }

        $name = $data['name'] ?? null;
        if (!is_string($name)) {
            return new JsonResponse(['error' => 'name is required'], 422);
        }

        try {
            $resource = $this->createResourceService->create($name);
        } catch (DomainException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 422);
        }

        return new JsonResponse([
            'id'        => $resource->id(),
            'name'      => $resource->name,
            'createdAt' => $resource->createdAt->format(\DateTimeInterface::ATOM),
        ], 201);
    }
}
