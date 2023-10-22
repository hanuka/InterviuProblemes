<?php

namespace App\Modules\Approval\Api;

use App\Domain\Enums\StatusEnum;
use App\Modules\Approval\Infrastructure\Repository\ApprovalRepository;
use Ramsey\Uuid\UuidInterface;

class ApprovalService
{

    public function getEntityStatus(
        UuidInterface $id,
        string $entity
    ): StatusEnum {
        $repository = new ApprovalRepository();

        return $repository->getEntityStatus($id, $entity);

    }
}
