<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        // Ambil semua user beserta role-nya
        $users = User::with('roles')->orderBy('name', 'asc')->get();
        // Ambil daftar role untuk ditampilkan di dropdown form (Admin, Cashier, Kitchen)
        $roles = Role::all();
        
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6',
            'role' => 'required|exists:roles,name',
            'is_active' => 'required|boolean'
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'is_active' => $request->is_active,
        ]);

        // Assign role dari Spatie
        $user->assignRole($request->role);

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil ditambahkan!'
        ]);
    }

    public function edit(User $user)
    {
        // Sertakan nama role yang sedang dipakai saat ini
        $user->role_name = $user->getRoleNames()->first();
        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:6', // Password opsional saat edit
            'role' => 'required|exists:roles,name',
            'is_active' => 'required|boolean'
        ]);

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'is_active' => $request->is_active,
        ];

        // Jika password diisi, maka update password
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Sync role (Timpa role lama dengan role baru)
        $user->syncRoles([$request->role]);

        return response()->json([
            'success' => true,
            'message' => 'Data pengguna berhasil diperbarui!'
        ]);
    }

    public function destroy(User $user)
    {
        // Cegah Admin menghapus dirinya sendiri
        if ($user->id === auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Anda tidak bisa menghapus akun Anda sendiri!'], 400);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil dihapus!'
        ]);
    }
}