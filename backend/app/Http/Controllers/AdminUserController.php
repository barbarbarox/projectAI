<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('status')) {
            if ($request->status === 'pending') {
                $query->where('is_verified', false);
            } elseif ($request->status === 'verified') {
                $query->where('is_verified', true);
            }
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        $pendingCount = User::where('is_verified', false)->count();

        return view('admin.users.index', compact('users', 'pendingCount'));
    }

    public function verify(User $user)
    {
        $user->update(['is_verified' => true]);
        return back()->with('success', "Akun pengguna {$user->name} berhasil disetujui.");
    }

    public function unverify(User $user)
    {
        $user->update(['is_verified' => false]);
        return back()->with('success', "Akun pengguna {$user->name} telah dibatalkan persetujuannya.");
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'Pengguna berhasil dihapus.');
    }
}
