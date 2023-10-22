<?php

namespace App\Modules\Approval\Application\Controller;

use App\Infrastructure\Controller;
use App\Modules\Approval\Api\ApprovalFacadeInterface;
use App\Modules\Approval\Api\ApprovalService;
use App\Modules\Approval\Api\Dto\ApprovalDto;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class RejectController extends Controller
{
    public function update(
        Request $request,
        ApprovalFacadeInterface $approvalFacade,
        ApprovalService $approvalService
    ) {
        $entity = $request->get('entity');

        $approveDTO = new ApprovalDto(
            Uuid::fromString($request->get('id')),
            $approvalService->getEntityStatus(Uuid::fromString($request->get('id')), $entity),
            $entity
        );

        if ($approvalFacade->reject($approveDTO)) {
            return response('Success', 200);
        }
    }
}
