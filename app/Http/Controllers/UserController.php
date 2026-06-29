<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = [
            'admin' => 'Admin',
            'kepala_dinas' => 'Kepala Dinas',
            'kepala_bidang' => 'Kepala Bidang',
            'ketua_tim' => 'Ketua Tim / Kapala Seksi',
            'user' => 'User',
        ];

        $devisis = [
            'SEKRETARIAT',
            'TATA RUANG',
            'JAKON',
            'PERTANAHAN',
            'TATA BANGUNAN',
        ];

        return view('users.create', compact('roles', 'devisis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:userlogin,username',
            'new_password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,kepala_dinas,kepala_bidang,ketua_tim,user',
            'devisi' => 'nullable|in:Sekretariat,Bidang Perencanaan,Bidang Pengendalian,Bidang Penegakan,Bidang Pelayanan,Bidang Umum',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['username', 'role', 'devisi']);
        $data['new_password'] = Hash::make($request->new_password);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('avatars', 'public');
            $data['image'] = $imagePath;
        }

        User::create($data);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = [
            'admin' => 'Admin',
            'kepala_dinas' => 'Kepala Dinas',
            'kepala_bidang' => 'Kepala Bidang',
            'ketua_tim' => 'Ketua Tim / Kapala Seksi',
            'user' => 'User',
        ];

        $devisis = [
            'SEKRETARIAT',
            'TATA RUANG',
            'JAKON',
            'PERTANAHAN',
            'TATA BANGUNAN',
        ];

        return view('users.edit', compact('user', 'roles', 'devisis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        dd($request->all());
        dd('stop');
        $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('userlogin')->ignore($user->id)],
            'new_password' => 'nullable|string|min:8|confirmed',
            'role' => 'sometimes|in:admin,kepala_dinas,kepala_bidang,ketua_tim,user',
            'devisi' => 'nullable|in:SEKRETARIAT,TATA RUANG,JAKON,PERTANAHAN,TATA BANGUNAN',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['username', 'role', 'devisi']);

        if ($request->filled('new_password')) {
            $data['new_password'] = Hash::make($request->new_password);
        }
        
        if ($request->hasFile('image')) {
            // Delete old image
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }
            
            $imagePath = $request->file('image')->store('avatars', 'public');
            $data['image'] = $imagePath;
        }
        
        $user->update($data);
        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Delete image if exists
        if ($user->image && Storage::disk('public')->exists($user->image)) {
            Storage::disk('public')->delete($user->image);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
    }
}