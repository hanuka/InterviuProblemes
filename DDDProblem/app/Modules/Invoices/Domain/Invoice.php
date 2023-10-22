<?php

namespace App\Modules\Invoices\Domain;

use App\Domain\Models\Company;
use App\Domain\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $keyType='string';

    protected $dates= ['date', 'due_date'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function product()
    {
        return $this->belongsToMany(Product::class, 'invoice_product_lines');
    }
}
