<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        
        // Filters
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        if ($request->filled('division_id')) {
            $query->where('division_id', $request->division_id);
        }
        
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        if ($request->filled('join_date_from')) {
            $query->where('join_date', '>=', $request->join_date_from);
        }
        
        if ($request->filled('join_date_to')) {
            $query->where('join_date', '<=', $request->join_date_to);
        }
        
        // Sorting
        $sort = $request->get('sort', 'name');
        $direction = $request->get('direction', 'asc');
        
        if (in_array($sort, ['name', 'email', 'join_date'])) {
            $query->orderBy($sort, $direction);
        }
        
        $users = $query->paginate(20);
        $divisions = Division::all();
        
        return view('admin.users.index', compact('users', 'divisions'));
    }
    
    public function create()
    {
        $divisions = Division::all();
        return view('admin.users.create', compact('divisions'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'username' => 'required|string|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,user,leader,hrd',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'division_id' => 'nullable|exists:divisions,id',
            'initial_leave_quota' => 'required|integer|min:0',
            'join_date' => 'required|date',
            'is_active' => 'boolean',
        ]);
        
        $validated['password'] = Hash::make($validated['password']);
        
        User::create($validated);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }
    
    public function edit(User $user)
    {
        $divisions = Division::all();
        return view('admin.users.edit', compact('user', 'divisions'));
    }
    
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'username' => ['required', 'string', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:admin,user,leader,hrd',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'division_id' => 'nullable|exists:divisions,id',
            'initial_leave_quota' => 'required|integer|min:0',
            'join_date' => 'required|date',
            'is_active' => 'boolean',
            'password' => 'nullable|string|min:8',
        ]);
        
        if ($request->filled('password')) {
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
        if (!in_array($user->role, ['user', 'leader'])) {
            return redirect()->back()
                ->with('error', 'Only users and leaders can be deleted.');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}