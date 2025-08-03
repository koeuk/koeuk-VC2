<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FixingProgress extends Model
{
    use HasFactory;

    protected $fillable=[
        'fixer_id',
        'booking_id',
        'action'
    ];
    public function fixer()
    {
        return $this->belongsTo(User::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
    
}
