<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category_type',
        'category_title',
        'price',
        'count',
        'status',
        'payed',
        'trackingNumber',
        'user_id',

    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
