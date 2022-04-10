<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleMonth extends Model
{
    use HasFactory;
    protected $table='monthly_sale_product';
    protected $fillable = 
    [
      'sale_name', 'product_id', 'sale_month','discount','sale_description',
    ];
 

}