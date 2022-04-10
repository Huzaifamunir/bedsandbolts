<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = 
    [
      'user_id', 'name',  'email','country','city','contact','address','zipcode','note','payment_method','total_product',
      'amount', 'order_date','status','products','size',
    ];
    public function transation()
    {
        
        return $this->belongsTo(Transation::class);
    }
}
