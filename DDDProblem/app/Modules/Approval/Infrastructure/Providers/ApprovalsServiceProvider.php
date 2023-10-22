<?php

declare(strict_types=1);

namespace App\Modules\Approval\Infrastructure\Providers;

use App\Modules\Approval\Api\ApprovalFacadeInterface;
use App\Modules\Approval\Api\Events\EntityApproved;
use App\Modules\Approval\Api\Events\EntityRejected;
use App\Modules\Approval\Api\Listeners\ApprovalSubscriber;
use App\Modules\Approval\Application\ApprovalFacade;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class ApprovalsServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->scoped(ApprovalFacadeInterface::class, ApprovalFacade::class);
    }

    /** @return array<class-string> */
    public function provides(): array
    {
        return [
            ApprovalFacadeInterface::class,
        ];
    }

    public function boot(): void
    {
        Event::listen(
            EntityApproved::class,
            [ApprovalSubscriber::class, 'handleInvoiceApprove']
        );

        Event::listen(
            EntityRejected::class,
            [ApprovalSubscriber::class, 'handleInvoiceReject']
        );
    }
}
