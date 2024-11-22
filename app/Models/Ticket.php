<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'order_id',
        'ticket_type',
        'barcode',
        'price',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
