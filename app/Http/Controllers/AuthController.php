<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function password()
    {
        return 'new_password'; // Or 'username', or whatever your column name is
    }

    public function index(Request $request) {
        $credentials = [
            'username' => $request['akun'],
            'password' => $request['password'],
        ];

        if(Auth::attempt($credentials)){
            return redirect()->route('dashboard')->with('success', 'Berhasil Login');
        }else{
            return redirect()->back()->with('error', 'Login Gagal Username atau Password Salah');
        }
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logout Berhasil');
    }

    public function profile_update(User $user, Request $request) {
        $data = $request->validate([
            'username' => 'required',
            'nip' => 'required',
            'image' => 'sometimes',
            'password' => 'sometimes|confirmed'
        ]);
        if($data['password']){
            $data['new_password'] = Hash::make($data['password']);
        }else{
            $data['new_password'] = $user->new_password;
        }
        if(isset($data['image'])){
            Storage::delete($user->image);  
            $data['image'] = $request->file('image')->store('image');
        }else{
            $data['image'] = $user->image;
        }
        $user->update($data);
        return redirect()->back()->with('success', 'Data berhasil diubah');
    }
}
