<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SofaOptions extends Model
{
    use HasFactory;
    protected $table="sofa_options";
    protected $fillable = 
    [
        'name', ];
        
        public function product()
    {
        return $this->hasMany(Product::class,'sofa_id','id');
        
    }
       
   
}
