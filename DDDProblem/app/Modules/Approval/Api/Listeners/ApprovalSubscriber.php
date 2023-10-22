<?php

namespace App\Modules\Approval\Api\Listeners;

use App\Modules\Approval\Api\Events\EntityApproved;
use App\Modules\Approval\Api\Events\EntityRejected;
use App\Modules\Approval\Infrastructure\Repository\ApprovalRepository;
use Illuminate\Events\Dispatcher;

class ApprovalSubscriber
{
    /**
     * Handle user login events.
     */
    public function handleInvoiceApprove($event) {
        $approvalRepository = new ApprovalRepository();
        $approvalRepository->approve($event->approvalDto);
    }

    /**
     * Handle user logout events.
     */
    public function handleInvoiceReject($event) {
        $approvalRepository = new ApprovalRepository();
        $approvalRepository->reject($event->approvalDto);
    }
}
