<?php

namespace App\Http\Controllers\Pengaturan;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\ManajemenUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Laravel\Facades\Image;

class ManajemenUserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $query = User::query();

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            if ($request->filled('sort_by')) {
                $sort_by = $request->sort_by;
                $sort_dir = $request->sort_dir ?? 'asc';
                $query->orderBy($sort_by, $sort_dir);
            }

            $data = $query->paginate($request->limit ?? 10);

            return $this->success('User berhasil diambil', $data->items(), $data->total());
        }

        return view('pengaturan.manajemen-user.index');
    }

    public function create()
    {
        return view('pengaturan.manajemen-user.create');
    }

    public function store(ManajemenUser\Store $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $file->hashName();
            $path = 'profile-photos/' . $filename;

            $encoded = Image::read($file)
                ->orient()
                ->resize(300, 300)
                ->toJpeg(75);

            Storage::disk('public')->put($path, $encoded);
            $user->update(['photo_path' => $path]);
        }

        return redirect()->route('pengaturan.manajemen-user.index')
            ->with('notification', [
                'type' => 'success',
                'title' => 'Berhasil',
                'message' => 'User berhasil ditambahkan',
            ]);
    }

    public function edit(User $manajemen_user)
    {
        if ($manajemen_user->id === auth()->id()) {
            return redirect()->back()
                ->with('notification', [
                    'type' => 'error',
                    'title' => 'Akses Ditolak',
                    'message' => 'Anda tidak dapat mengedit akun sendiri dari menu ini. Silakan gunakan menu Profil.',
                ]);
        }
        return view('pengaturan.manajemen-user.edit', ['user' => $manajemen_user]);
    }

    public function update(ManajemenUser\Update $request, User $manajemen_user)
    {
        if ($manajemen_user->id === auth()->id()) {
            return redirect()->back()
                ->with('notification', [
                    'type' => 'error',
                    'title' => 'Akses Ditolak',
                    'message' => 'Anda tidak dapat mengedit akun sendiri dari menu ini. Silakan gunakan menu Profil.',
                ]);
        }

        $validated = $request->validated();

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $manajemen_user->update($validated);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $file->hashName();
            $path = 'profile-photos/' . $filename;

            $encoded = Image::read($file)
                ->orient()
                ->resize(300, 300)
                ->toJpeg(75);

            Storage::disk('public')->put($path, $encoded);

            if ($manajemen_user->photo_path && Storage::disk('public')->exists($manajemen_user->photo_path)) {
                Storage::disk('public')->delete($manajemen_user->photo_path);
            }

            $manajemen_user->update(['photo_path' => $path]);
        }
        return redirect()->route('pengaturan.manajemen-user.index')
            ->with('notification', [
                'type' => 'success',
                'title' => 'Berhasil',
                'message' => 'User berhasil diupdate',
            ]);
    }

    public function destroy(User $manajemen_user)
    {
        if ($manajemen_user->id === auth()->id()) {
            return redirect()->route('pengaturan.manajemen-user.index')
                ->with('notification', [
                    'type' => 'error',
                    'title' => 'Akses Ditolak',
                    'message' => 'Anda tidak dapat menghapus diri sendiri.',
                ]);
        }

        if (User::count() <= 1) {
            return redirect()->route('pengaturan.manajemen-user.index')
                ->with('notification', [
                    'type' => 'error',
                    'title' => 'Gagal',
                    'message' => 'Tidak dapat menghapus user terakhir.',
                ]);
        }

        if ($manajemen_user->photo_path && Storage::disk('public')->exists($manajemen_user->photo_path)) {
            Storage::disk('public')->delete($manajemen_user->photo_path);
        }

        $manajemen_user->delete();

        return redirect()->route('manajemen-user.index')
            ->with('notification', [
                'type' => 'success',
                'title' => 'Berhasil',
                'message' => 'User berhasil dihapus',
            ]);
    }

    public function updateStatus(Request $request, User $manajemen_user)
    {
        if ($manajemen_user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'type' => 'error',
                'title' => 'Akses Ditolak',
                'message' => 'Anda tidak dapat mengubah status diri sendiri.',
            ], 403);
        }

        if (User::count() <= 1) {
            return response()->json([
                'success' => false,
                'type' => 'error',
                'title' => 'Gagal',
                'message' => 'Tidak dapat mengubah status user terakhir.',
            ], 400);
        }

        $newStatus = $manajemen_user->status === 'active' ? 'inactive' : 'active';
        $manajemen_user->update(['status' => $newStatus]);

        return redirect()->route('pengaturan.manajemen-user.index')
            ->with('notification', [
                'type' => 'success',
                'title' => 'Berhasil',
                'message' => 'Status user berhasil diubah',
            ]);
    }
}
