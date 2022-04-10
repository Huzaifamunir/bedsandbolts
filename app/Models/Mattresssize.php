<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mattresssize extends Model
{
    use HasFactory;
    public $fillable=
    [
    'name',  'price','image',
    ];
    public function product()
    {
        return $this->hasMany(Product::class,'mattress_id','id');
        
    }
}
