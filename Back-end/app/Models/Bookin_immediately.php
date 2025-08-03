<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookin_immediately extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_id', 'user_id','date', 'message','latitude',
        'longitude','promotion_id'
    ];

    
    public function service(){
        return $this->belongsTo(Service::class,'service_id','id');
    }
}
