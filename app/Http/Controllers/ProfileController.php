<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = $file->hashName();
            $path = 'profile-photos/' . $filename;

            // Resize and encode
            $encoded = Image::read($file)
                ->orient()
                ->resize(300, 300)
                ->toJpeg(75);

            // Save to public disk
            Storage::disk('public')->put($path, $encoded);

            // Delete old photo if exists
            if ($request->user()->photo_path && Storage::disk('public')->exists($request->user()->photo_path)) {
                Storage::disk('public')->delete($request->user()->photo_path);
            }

            $request->user()->photo_path = $path;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')
            ->with('notification', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Profile berhasil diupdate.'
            ]);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        if (User::count() <= 1) {
            return Redirect::route('profile.edit')
                ->with('notification', [
                    'type' => 'error',
                    'title' => 'Gagal!',
                    'message' => 'User tidak dapat dihapus karena merupakan user terakhir.'
                ]);
        }

        $user = $request->user();

        Auth::logout();

        if ($user->photo_path && Storage::disk('public')->exists($user->photo_path)) {
            Storage::disk('public')->delete($user->photo_path);
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
