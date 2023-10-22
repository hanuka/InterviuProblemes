<?php

namespace App\Modules\Invoices\ValueObject;

use App\Domain\ValueObject\Date;

/**
 * TODO:
 * I created this for show endpoint to show all the invoice details but I used it for accepted and reject
 * endpoints, those endpoints need new DTO only with uuid status and entity
 */
class InvoiceInfo
{
    private float $totalPrice;
    private string $invoiceNumber;
    private Date $invoiceDate;
    private Date $dueDate;

    public function __construct(float $totalPrice, string $invoiceNumber, Date $invoiceDate, Date $dueDate)
    {
        if ($totalPrice < 0) {
            throw new \InvalidArgumentException("Total price cannot be negative.");
        }

        if (empty($invoiceNumber)) {
            throw new \InvalidArgumentException("Invoice number cannot be empty.");
        }

        $this->totalPrice = $totalPrice;
        $this->invoiceNumber = $invoiceNumber;
        $this->invoiceDate = $invoiceDate;
        $this->dueDate = $dueDate;
    }

    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    public function getInvoiceNumber(): string
    {
        return $this->invoiceNumber;
    }

    public function getInvoiceDate(): Date
    {
        return $this->invoiceDate;
    }

    public function getDueDate(): Date
    {
        return $this->dueDate;
    }

    public function __toArray(): array
    {
        return [
            'totalPrice' => $this->getTotalPrice(),
            'invoiceNumber' => $this->getInvoiceNumber(),
            'invoiceDate' => $this->getInvoiceDate(),
            'dueDate' => $this->getDueDate()
            ];
    }
}
