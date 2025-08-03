<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'discount',
        'description',
        'start_date',
        'end_date',

    ];

    // public function promotions(): HasMany
    // {
    //     return $this->hasMany(Promotion::class, 'promotions');
    // }

    // public static function list()
    // {
    //     return self::all();
    // }

    // public static function store($request, $id = null)
    // {
    //     $data = $request->only('discount', 'description', 'start_date', 'end_date');
    //     $discount = self::updateOrCreate(['id' => $id], $data);
    //     $discount->services()->sync($request->service_id);
    //     return $discount;
    // }
}
