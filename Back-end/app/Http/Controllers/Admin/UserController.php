<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('role_or_permission:User access|User create|User edit|User delete', ['only' => ['index', 'show']]);
        $this->middleware('role_or_permission:User create', ['only' => ['create', 'store']]);
        $this->middleware('role_or_permission:User edit', ['only' => ['edit', 'update']]);
        $this->middleware('role_or_permission:User delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $users = User::paginate(5);

        return view('setting.user.index', ['users' => $users]);
    }

    public function create()
    {
        $roles = Role::all();
        return view('setting.user.new', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'address' => 'required',
            'password' => 'required|confirmed',
            'profile' => 'required|file|max:2048',
        ]);

        $data = $request->all();
        if ($request->hasFile('profile')) {
            $profile = $request->file('profile');
            $profileContents = file_get_contents($profile->getRealPath());
            $base64String = 'data:' . $profile->getMimeType() . ';base64,' . base64_encode($profileContents);
            $data['profile'] = $base64String;
        } else {
            $data['profile'] = null;
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'address' => $data['address'],
            'profile' => $data['profile'],
            'phone' => $data['phone'],
            'role' => $data['role'],
            'password' => bcrypt($data['password']),
        ]);
        $user->save();

        return redirect('admin/users')
            ->with('showAlertCreate', true);
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('setting.user.edit', ['user' => $user], compact('roles'));
    }

    // app/Http/Controllers/UserController.php
    public function update(Request $request, User $user)
    {
        // Validate the request data
        $request->validate([
            'role' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'address' => 'required',
        ]);

        $user->update($request->all());
        return redirect('admin/users')
            ->with('showAlertEdit', true);
    }


    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user_id = Auth::id();
        $user = User::find($user_id);

        if ($request->hasFile('profile')) {
            $image = $request->file('profile');
            $imageBase64 = base64_encode(file_get_contents($image->getRealPath()));

            $base64String = 'data:image/' . $image->getClientOriginalExtension() . ';base64,' . $imageBase64;
            $user->profile = $base64String;
        }

        $user->save();

        return response()->json([
            'message' => 'User profile image updated successfully',
            'user' => $user,
        ], 200);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect('admin/users')
            ->with('showAlertDelete', true);
    }

    public function updateInformation(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
        ]);

        // Update the user data
        $user = Auth::user();
        $user->name = $request->input('name');
        $user->phone = $request->input('phone');
        $user->save();

        return response()->json(['message' => 'User updated successfully']);
    }
}
