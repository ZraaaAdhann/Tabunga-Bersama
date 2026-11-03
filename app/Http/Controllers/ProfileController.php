<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        if ($request->hasFile('avatar')) {
            // Hapus file lama jika berupa path file lokal biasa
            if ($user->avatar && !str_starts_with($user->avatar, 'http') && !str_starts_with($user->avatar, 'data:')) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Convert foto avatar ke Base64 Data URI
            $file = $request->file('avatar');
            $base64Image = base64_encode(file_get_contents($file->getRealPath()));
            $mimeType = $file->getClientMimeType();

            $validated['avatar'] = 'data:' . $mimeType . ';base64,' . $base64Image;
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', ['password' => ['required', 'current_password']]);

        $user = $request->user();
        Auth::logout();

        // Hapus file avatar lokal jika ada
        if ($user->avatar && !str_starts_with($user->avatar, 'http') && !str_starts_with($user->avatar, 'data:')) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}