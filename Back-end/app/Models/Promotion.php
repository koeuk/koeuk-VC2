<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'discount_id',
        'service_id',
    ];

    public function discount() :BelongsTo
    {
        return $this->belongsTo(Discount::class,'discount_id', 'id');
    }
    public function service() :BelongsTo
    {
        return $this->belongsTo(Service::class,'service_id', 'id');
    }

    public static function list(){
        return self::all();
    }
    public static function store($request, $id = null)
    {
        $data = $request->only('discount_id', 'service_id');
        $discount = self::updateOrCreate(['id' => $id], $data);
        $discount->discount();
        $discount->service();
        return $discount;
    }
}


