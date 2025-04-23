<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Asset;

class VendorModel extends Authenticatable
{
    use HasFactory , Notifiable;
    protected $guard = 'vendor'; 

    protected $table="vendor";
    protected $fillable=['assigned_hoarding_id','category','sub_category','name','email','contact','contact_persons','state','city','role','password','plain_password','status'];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getAssetsAttribute()
    {
        $hoardingIds = json_decode($this->assigned_hoarding_id, true);

        // Safeguard: if decoding fails or $hoardingIds is not an array, return empty collection
        if (!is_array($hoardingIds) || empty($hoardingIds)) {
            return collect(); // return empty Laravel collection
        }

        return Asset::whereIn('hoarding_id', $hoardingIds)->get();
    }

    
    public function campaigns()
    {
        return $this->belongsToMany(CampaignModel::class, 'campaign_vendor', 'vendor_id', 'campaign_id');
    }

    
}
