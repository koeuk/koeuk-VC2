<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','booking_type_id','action'];
    
    public function booking_type(){
        return $this->belongsTo(Booking::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function fixer()
    {
        return $this->belongsTo(User::class, 'fixer_id');
    }
    public function booking_immedately(){
        return $this->belongsTo(Bookin_immediately::class,'booking_type_id','id');
    }
    public function booking_deadline(){
        return $this->belongsTo(Bookin_deadline::class,'booking_type_id','id');
    }
    public function service(){
        return $this->belongsTo(Service::class);
    }
    
}
