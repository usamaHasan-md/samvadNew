<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // 👈 Change Model Inheritance
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class FieldAgentModel extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    
    protected $guard = 'fieldagent'; 

    protected $table="fieldagent";
    protected $fillable=['name','added_by','number','role','password', 'confirmation_password', 'status'];

    protected $hidden = [
        'password', 'remember_token',
    ];


    public function campaigns()
    {
        return $this->belongsToMany(CampaignModel::class, 'campaign_fieldagent', 'fieldagent_id', 'campaign_id');
    }
    
}
