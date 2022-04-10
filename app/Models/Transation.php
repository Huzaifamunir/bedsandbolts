<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transation extends Model
{
    use HasFactory;
    protected $fillable = 
    [
      'user_id', 'name', 'order_id', 'email','account','amount','card_type',
    ];
    
    public function users()
    {
        return $this->hasMany(User::class,'user_id','id');
        
    }
    public function orders()
    {
        return $this->hasMany(Order::class,'user_id','id');
        
    }

}
