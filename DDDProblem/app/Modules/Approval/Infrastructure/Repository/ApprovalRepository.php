<?php

namespace App\Modules\Approval\Infrastructure\Repository;

use App\Domain\Enums\StatusEnum;
use App\Modules\Approval\Api\Dto\ApprovalDto;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\UuidInterface;

class ApprovalRepository
{
    public function approve(ApprovalDto $approvalDto): int
    {
        return DB::table($approvalDto->entity)
            ->where('id', $approvalDto->id)->update(['status' => StatusEnum::APPROVED]);
    }

    public function reject(ApprovalDto $approvalDto): int
    {
        return DB::table($approvalDto->entity)
            ->where('id', $approvalDto->id)->update(['status' => StatusEnum::REJECTED]);
    }


    public function getEntityStatus(
        UuidInterface $id,
        string $entity
    ): StatusEnum {
        return StatusEnum::tryFrom(DB::table($entity)->find($id)->status);
    }

}
