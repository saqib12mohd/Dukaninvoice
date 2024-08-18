<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'number',
        'date',
        'sale',

    ];


    public function ItemDataEntry()
    {
        return $this->hasMany(Itementry::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}


