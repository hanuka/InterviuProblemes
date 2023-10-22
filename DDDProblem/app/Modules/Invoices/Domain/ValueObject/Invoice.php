<?php

namespace App\Modules\Invoices\Domain\ValueObject;

use App\Domain\ValueObject\Date;

final readonly class Invoice
{
    public function __construct(
        private string $invoiceNumber,
        private Date $invoiceDate,
        private string $id
    )
    {
    }

    public function getInvoiceNumber(): string
    {
        return $this->invoiceNumber;
    }

    public function getInvoiceDate(): Date
    {
        return $this->invoiceDate;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
