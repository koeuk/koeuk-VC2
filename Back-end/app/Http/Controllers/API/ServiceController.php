<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
{
    $services = Service::all();
    $services = ServiceResource::Collection($services);
    $topService = $services->sortByDesc('rating')->take(3);

    return response()->json([
        'services' => $services,
        'topService' => $topService,
    ]);
}
}
