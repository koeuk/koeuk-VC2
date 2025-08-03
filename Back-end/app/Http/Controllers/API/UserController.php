<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Retrieve all users with the role 'fixer'
        $fixers = User::all()->where('role', 'fixer')->all();

        // Return users as a JSON response
        return response()->json($fixers);
    }
}
