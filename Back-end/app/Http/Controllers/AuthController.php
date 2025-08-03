<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required|string|max:255',
            'password'  => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'User not found'
            ], 401);
        }

        $user   = User::where('email', $request->email)->firstOrFail();
        $token  = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'       => 'Login success',
            'access_token'  => $token,
            'token_type'    => 'Bearer',
            'user' => $user
        ]);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        // $permissions = $user->getAllPermissions();
        // $roles = $user->getRoleNames();
        return response()->json([
            'message' => 'Login success',
            'data' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logout successful'
        ]);
    }
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
            'remember_token' => Str::random(20),
        ]);

        $user->assignRole('user');
        $user->givePermissionTo(['Mail access']); // Adjust as per your application's needs

        $tokenResult = $user->createToken('auth_token');

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'access_token' => $tokenResult->plainTextToken,
            'token_type' => 'Bearer',
        ], 201);
    }
    public function updateInformation(Request $request)
    {
        $request->validate([
            'name' => 'nullable',
            'phone' => 'nullable'
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->save();
        return response()->json(['message' => 'User updated successfully', 'user' => $user]);


    }

    public function fixerRegister(Request $request): JsonResponse
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string', 
        'password' => 'required|string|min:8',
        'location' => 'required|string|max:255', 
        'phone' => 'required|string', 
        'career' => 'required|string|max:255'
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'location' => $request->location, 
        'phone' => $request->phone, 
        'career' => $request->career, 
        'email_verified_at' => now(),
        'remember_token' => Str::random(20),
        'role' => 'fixer'
    ]);

    $user->assignRole('fixer');
    $user->givePermissionTo(['Mail access']); 

    $tokenResult = $user->createToken('auth_token');

    return response()->json([
        'message' => 'User registered successfully',
        'user' => $user,
        'access_token' => $tokenResult->plainTextToken,
        'token_type' => 'Bearer',
    ], 201);
}

    public function updateProfile(Request $request, $id)
    {
        $user = Auth::user(); // Retrieve authenticated user
    
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
    
        // Check if the request contains a file
        if ($request->hasFile('profile')) {
            $image = $request->file('profile');
    
            // Read the file contents and encode it as base64
            $imageBase64 = base64_encode(file_get_contents($image->getRealPath()));
    
            // Build the base64 data URI with the appropriate image extension
            $base64String = 'data:image/' . $image->getClientOriginalExtension() . ';base64,' . $imageBase64;
    
            // Update the user's profile field with the base64 string
            $user->profile = $base64String;
    
            // Save the user model
            $user->save();
    
            // Return success response
            return response()->json([
                'message' => 'User profile updated successfully',
                'user' => $user,
            ], 200);
        }
    
        return response()->json(['error' => 'No file uploaded.'], 400);
    }
}