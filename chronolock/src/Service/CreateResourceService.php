<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Resource;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;

final class CreateResourceService
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    /**
     * @throws DomainException
     */
    public function create(string $name): Resource
    {
        $name = trim($name);

        if ($name === '') {
            throw new DomainException('Resource name cannot be empty');
        }

        $resource = new Resource($name);

        $this->em->persist($resource);
        $this->em->flush();

        return $resource;
    }
}