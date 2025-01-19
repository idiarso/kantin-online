<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function teachers()
    {
        $users = User::where('role', 'teacher')
            ->latest()
            ->paginate(10);

        return view('admin.users.teachers', compact('users'));
    }

    public function students()
    {
        $users = User::where('role', 'student')
            ->latest()
            ->paginate(10);

        return view('admin.users.students', compact('users'));
    }

    public function parents()
    {
        $users = User::where('role', 'parent')
            ->latest()
            ->paginate(10);

        return view('admin.users.parents', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(UserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        
        User::create($data);
        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user)
    {
        $data = $request->validated();
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);
        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully');
    }

    public function toggleStatus(User $user)
    {
        $user->status = !$user->status;
        $user->save();
        
        return response()->json(['success' => true]);
    }
} 