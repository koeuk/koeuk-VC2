<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
   
        $promotions = Discount::all();
        return response()->json($promotions);
        
    }
}
