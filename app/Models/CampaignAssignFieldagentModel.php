<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignAssignFieldagentModel extends Model
{
    protected $table="campaign_fieldagent";
    protected $fillable = [
        'campaign_id',
        'fieldagent_id',
        'city',
        'latitude',
        'longitude',
        // Add more fields here as needed
    ];
}
