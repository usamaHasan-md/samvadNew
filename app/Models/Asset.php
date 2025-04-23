<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{    
    
        protected $table = 'assets'; // Optional if model name doesn't match
        protected $fillable = [
            'hoarding_id', 'category', 'sub_category', 'state',
            'district', 'district_area', 'location_address'
        ];

        // Asset.php

        public function category()
        {
            return $this->belongsTo(Category::class, 'category');
        }
        
        public function subCategory()
        {
            return $this->belongsTo(SubCategory::class, 'sub_category');
        }
        
        public function categoryData()
        {
            return $this->belongsTo(Category::class, 'category');
        }
        
        public function subCategoryData()
        {
            return $this->belongsTo(SubCategory::class, 'sub_category');
        }
        

    
    }
