<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table ='products';
    protected $fillable = [
        'name','categories_id', 'price','image','description','bedsize_id','mattress_id','fabric_id','febric_price','beeding_id','beading_price',
        'addon_id','buttondiomand_price','buttondiamond_image','sofa_id','liftup_storage','stool_id','liftup_price','is_featured','matching_storage_id',
        'matching_storagebox_price','matching_storagebox_image','is_sale',
    ];
    public function categories()
    {
        
        return $this->belongsTo(Category::class);
    }
    public function mattress()
    {
        
        return $this->belongsTo(Mattresssize::class);
    }
    public function fabrics()
    {
        
        return $this->belongsTo(Fabric::class);
    }
    public function bedsize()
    {
        
        return $this->belongsTo(Bedsize::class);
    }
    public function beeding()
    {
        
        return $this->belongsTo(Beeding::class);
    }
    public function addon()
    {
        
        return $this->belongsTo(Addon::class);
    }
    public function matching()
    {
        
        return $this->belongsTo(Matching_storage::class);
    }
    
      public function sofa()
    {
        
        return $this->belongsTo(SofaOptions::class);
    }
    
    
    public function cartadd()
    {
        
        return $this->belongsTo(Product::class);
    }
}
