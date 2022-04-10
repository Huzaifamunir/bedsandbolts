<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beeding extends Model
{
    use HasFactory;
    public $fillable =
    [
        'name',  'price','image',
    ];
    public function product()
    {
        return $this->hasMany(Product::class,'beeding_id','id');
        
    }
}
