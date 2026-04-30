<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\ActivityLog;

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
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        //  Validation
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc,dns', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        // Old values store 
        $oldData = [
            'name' => $user->name,
            'email' => $user->email,
        ];


        // Fill new data
        $user->fill($validated);


        // Check if email changed
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Save
        $user->save();

        // New values
        $newData = [
            'name' => $user->name,
            'email' => $user->email,
        ];


        
        // Activity Log
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'profile.updated',
            'description' => 'Profile updated: ' . collect($oldData)->map(function ($value, $key) use ($newData) {
                return "{$key} changed from [{$value}] to [{$newData[$key]}]";
            })->implode('; '),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
