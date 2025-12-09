<?php


declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\Exception\LockConflictedException;

final class LockService
{
    public function __construct(
        private LockFactory $chronolockResourceFactory
    ) {}

    public function acquireResourceLock(int $resourceId): \Symfony\Component\Lock\LockInterface
    {
        $lockKey = sprintf('resource_lock_%d', $resourceId);

        $lock = $this->chronolockResourceFactory->createLock($lockKey, 5.0);

        if (!$lock->acquire()) {
            throw new LockConflictedException(sprintf('Resource %d is locked', $resourceId));
        }

        return $lock;
    }
}