<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class StripeSetting extends Model
{
    protected $table = 'StripeSettings';
    protected $fillable = 
    [
          'token','secrete_key','status',
    ];
    /**
     * Get the user associated with the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
   
   
}