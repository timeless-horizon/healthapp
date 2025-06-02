<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceFactory> */
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'generated_at',
        'duration',
        'due_date',
        'amount',
        'status',
        'user_id',
        'plan_id',
        'reference',
        'payment_method',
        'currency',
        'paid_at',
    ];


}
