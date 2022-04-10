<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    protected $table = 'contacts';
    protected $fillable = 
    [
        'name', 'email','price','message'];
        public function product()
        {
            // return $this->hasMany(Product::class,'','id');
            
        }
   
}
