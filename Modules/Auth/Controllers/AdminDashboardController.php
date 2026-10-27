<?php

namespace Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalRegularUsers = User::where('role', 'user')->count();
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalAdmins',
            'totalRegularUsers'
        ));
    }

    // Tambahan untuk Poin A: Menampilkan daftar semua user
    public function userList()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    // Tambahan untuk Poin B: Mengubah role user lain
    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'role' => 'required|in:admin,user',
        ]);

        $user->update([
            'role' => $request->role
        ]);

        return redirect()->route('admin.users')->with('success', 'Role user berhasil diubah!');
    }

    // Tambahan untuk Poin C: Menghapus user
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        // Mencegah admin menghapus akunnya sendiri yang sedang aktif login
        if ((int) $user->id === (int) auth()->id()) {
            return redirect()->route('admin.users')->with('error', 'Anda tidak dapat menghapus akun sendiri!');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus!');
    }
}
