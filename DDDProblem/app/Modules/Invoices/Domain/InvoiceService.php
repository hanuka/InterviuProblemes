<?php

namespace App\Modules\Invoices\Domain;

use App\Domain\ValueObject\Address;
use App\Domain\ValueObject\Company;
use App\Domain\ValueObject\Date;
use App\Domain\ValueObject\Product;
use App\Modules\Invoices\Domain\Dto\InvoiceDto;
use App\Modules\Invoices\Domain\ValueObject\Invoice as InvoiceVO;

class InvoiceService
{
    public function buildDto(Invoice $invoice): InvoiceDto
    {
        $invoice->load(['company', 'product' => function($query){
            $query->withPivot('quantity');
        }]);

        $productsVO = [];
        $totalPrice = 0;
        $addressVO = new Address(
            $invoice->company->street,
            $invoice->company->city,
            $invoice->company->zip,
            $invoice->company->phone
        );

        $companyVO = new Company(
            $invoice->company->name,
            $addressVO
        );

        $dateVO = new Date(
            $invoice->date->year,
            $invoice->date->month,
            $invoice->date->day
        );

        $invoiceDetails = new InvoiceVO(
            $invoice->number,
            $dateVO,
            $invoice->id
        );

        foreach ($invoice->product as $product) {
            $totalPrice+= $product->pivot->quantity * $product->price;
            $productsVO[] = new Product($product->name, $product->pivot->quantity, $product->price);
        }

        return new InvoiceDto($productsVO, $companyVO, $totalPrice, $invoiceDetails);
    }
}
