<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function cartProducts()
    {
        return $this->hasMany(cartProduct::class);
    }
    //
}
