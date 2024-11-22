<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'event_id',
        'event_date',
        'equal_price',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
