<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageUploadCampaignModel extends Model
{
    protected $table="imageuploadcamp";
    protected $fillable = [
        'campaign_id',
        'hoarding_id',
        'fieldagent_id',
        'image',
        'latitude',
        'longtitude',
        'date',
        'is_verified',
        'vendor_remarks',
        'verified_by',
        'verified_at',
    ];

    public function fieldAgent()
    {
        return $this->belongsTo(FieldAgentModel::class, 'fieldagent_id');
    }

public function verifiedBy()
{
    return $this->belongsTo(VendorModel::class, 'verified_by');
}
    

}