<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DivisionController extends Controller
{
    public function index(Request $request)
    {
        $query = Division::with(['leader', 'members']);
        
        if ($request->filled('name')) {
            $query->where('name', 'like', "%{$request->name}%");
        }
        
        if ($request->filled('leader_id')) {
            $query->where('leader_id', $request->leader_id);
        }
        
        $sort = $request->get('sort', 'name');
        $direction = $request->get('direction', 'asc');
        
        if (in_array($sort, ['name', 'formed_date'])) {
            $query->orderBy($sort, $direction);
        } elseif ($sort === 'member_count') {
            $query->withCount('members')->orderBy('members_count', $direction);
        }
        
        $divisions = $query->paginate(15);
        $leaders = User::where('role', 'leader')->get();
        
        return view('admin.divisions.index', compact('divisions', 'leaders'));
    }
    
    public function create()
    {
        $availableLeaders = User::where('role', 'leader')
            ->whereDoesntHave('leadDivision')
            ->get();
            
        return view('admin.divisions.create', compact('availableLeaders'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:divisions|max:255',
            'description' => 'nullable|string',
            'leader_id' => ['nullable', 'exists:users,id', Rule::exists('users', 'id')->where('role', 'leader')],
            'formed_date' => 'required|date',
        ]);
        
        Division::create($validated);
        
        return redirect()->route('admin.divisions.index')
            ->with('success', 'Division created successfully.');
    }
    
    public function edit(Division $division)
    {
        $availableLeaders = User::where('role', 'leader')
            ->where(function($query) use ($division) {
                $query->whereDoesntHave('leadDivision')
                    ->orWhere('id', $division->leader_id);
            })
            ->get();
            
        return view('admin.divisions.edit', compact('division', 'availableLeaders'));
    }
    
    public function update(Request $request, Division $division)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', Rule::unique('divisions')->ignore($division->id), 'max:255'],
            'description' => 'nullable|string',
            'leader_id' => ['nullable', 'exists:users,id', Rule::exists('users', 'id')->where('role', 'leader')],
            'formed_date' => 'required|date',
        ]);
        
        $division->update($validated);
        
        return redirect()->route('admin.divisions.index')
            ->with('success', 'Division updated successfully.');
    }
    
    public function destroy(Division $division)
    {
        // Detach all members
        $division->members()->update(['division_id' => null]);
        
        $division->delete();
        
        return redirect()->route('admin.divisions.index')
            ->with('success', 'Division deleted successfully.');
    }
    
    public function members(Division $division)
    {
        $members = $division->members()->paginate(20);
        return view('admin.divisions.members', compact('division', 'members'));
    }
    
    public function addMember(Request $request, Division $division)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:users,division_id,NULL,id,division_id,' . $division->id,
        ]);
        
        $user = User::find($validated['user_id']);
        $user->division_id = $division->id;
        $user->save();
        
        return redirect()->back()
            ->with('success', 'Member added successfully.');
    }
    
    public function removeMember(Division $division, User $user)
    {
        if ($user->division_id === $division->id) {
            $user->division_id = null;
            $user->save();
        }
        
        return redirect()->back()
            ->with('success', 'Member removed successfully.');
    }
}