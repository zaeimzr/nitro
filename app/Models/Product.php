<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category_type',
        'rate_per_toman',
        'min',
        'max',
        'description',
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
