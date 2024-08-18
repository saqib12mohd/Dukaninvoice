<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itementry extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'invoice_id',
        'qty',
        'rate',
        'amount',
        'discount',
        'discription',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
