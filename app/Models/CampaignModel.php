<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignModel extends Model
{
    protected $table = "campaign"; // Table name
    // protected $fillable = ['id','vendor_id','fieldagent_id','campaign_name', 'images', 'pdf', 'description','start_date','end_date'];
    protected $fillable = [
        'id', 'vendor_id', 'fieldagent_id', 'campaign_name',
        'images', 'pdf', 'description', 'category', 'sub_category',
        'start_date', 'end_date'
    ];


    public function vendors()
    {
        return $this->belongsToMany(VendorModel::class, 'campaign_vendor', 'campaign_id', 'vendor_id');
    }

    public function fieldAgents()
    {
         return $this->belongsToMany(FieldAgentModel::class, 'campaign_fieldagent', 'campaign_id', 'fieldagent_id')
                ->withPivot('hoarding_id')
                ->withTimestamps();
    }

}


