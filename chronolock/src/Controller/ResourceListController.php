<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ResourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class ResourceListController extends AbstractController
{
    public function __construct(
        private ResourceRepository $resources
    ) {}

    #[Route('/api/resources', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $all = $this->resources->findAll();

        $payload = array_map(static function ($r) {
            return [
                'id'        => $r->id(),
                'name'      => $r->name,
                'createdAt' => $r->createdAt->format(\DateTimeInterface::ATOM),
            ];
        }, $all);

        return new JsonResponse($payload);
    }
}