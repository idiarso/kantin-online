<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['admin', 'seller', 'student', 'teacher'])],
            'phone' => ['nullable', 'string', 'max:20'],
            'class' => ['nullable', 'string', 'max:50'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        
        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'seller', 'student', 'teacher'])],
            'phone' => ['nullable', 'string', 'max:20'],
            'class' => ['nullable', 'string', 'max:50'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function updateBalance(Request $request, User $user)
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0'],
            'type' => ['required', Rule::in(['add', 'subtract'])],
        ]);

        $amount = $validated['amount'];
        if ($validated['type'] === 'subtract') {
            $amount = -$amount;
        }

        $user->balance += $amount;
        $user->save();

        // Create transaction record
        $user->transactions()->create([
            'type' => $validated['type'] === 'add' ? 'topup' : 'refund',
            'amount' => abs($amount),
            'description' => 'Balance adjustment by admin',
            'status' => 'completed',
        ]);

        return back()->with('success', 'Balance updated successfully.');
    }
} 