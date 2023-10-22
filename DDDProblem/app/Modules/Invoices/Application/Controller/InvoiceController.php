<?php

namespace App\Modules\Invoices\Application\Controller;

use App\Infrastructure\Controller;
use App\Modules\Invoices\Domain\Dto\InvoiceDto;
use App\Modules\Invoices\Domain\Invoice;
use App\Modules\Invoices\Domain\InvoiceService;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class InvoiceController extends Controller
{

    public function index()
    {
        return view('Invoices.resources.views.index', ['invoices' => Invoice::all()]);
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['company','product']);
        return view('Invoices.resources.views.show', ['invoice' => $invoice]);
    }

    /**
     * TODO:
     * Remove these endpoints and use the Approval controllers, I created these
     * because I used the views and keept them like these, needs refactoring
     */
    public function approve(Invoice $invoice, InvoiceService $invoiceService): Response
    {
        $invoiceDTO = $invoiceService->buildDto($invoice);
        return Http::patch(route('approve.update'), $invoiceDTO->toArray());
    }

    public function reject(Invoice $invoice, InvoiceService $invoiceService): Response
    {
        $invoiceDTO = $invoiceService->buildDto($invoice);
        return Http::patch(route('reject.update'), $invoiceDTO->toArray());
    }

}
