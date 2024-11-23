<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'event_id',
        'event_date',
        'equal_price',
    ];

    public function ticket(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
