<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class AdminModel extends Authenticatable
{
    use HasFactory ,Notifiable;

    protected $guard ='admin'; 


    protected $table='admin';

    protected $fillable=['name','email','password','role'];
    protected $hidden=['password',];
    protected $casts=[
        'password'=>'hashed',
    ];
}
