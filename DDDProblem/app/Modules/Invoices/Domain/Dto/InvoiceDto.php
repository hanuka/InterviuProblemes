<?php

namespace App\Modules\Invoices\Domain\Dto;

use App\Domain\ValueObject\Company;
use App\Domain\ValueObject\Product;
use App\Modules\Invoices\Domain\Invoice as InvoiceEntity;
use App\Modules\Invoices\Domain\ValueObject\Invoice;

class InvoiceDto
{
    public function __construct(
        private readonly array   $products,
        private readonly Company $company,
        private readonly float   $totalPrice,
        private readonly Invoice $invoiceDetails
    ) {
    }

    /**
     * @return Product[]
     */
    public function getProducts(): array
    {
        $productArray = [];
        foreach ($this->products as $product) {
            $productArray[] = $product->toArray();
        }

        return $productArray;
    }

    public function getCompany(): array
    {
        return $this->company->toArray();
    }

    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    public function getInvoiceDetails(): Invoice
    {
        return $this->invoiceDetails;
    }

    public function toArray(): array
    {
        return [
            'product'       => $this->getProducts(),
            'company'       => $this->getCompany(),
            'invoiceDate'   => $this->getInvoiceDetails()->getInvoiceDate()->toString(),
            'totalPrice'    => $this->getTotalPrice(),
            'invoiceNumber' => $this->getInvoiceDetails()->getInvoiceNumber(),
            'entity'        => (new InvoiceEntity())->getTable(),
            'id'            => $this->getInvoiceDetails()->getId()
        ];
    }
}
