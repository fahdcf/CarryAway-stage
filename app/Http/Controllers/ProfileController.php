<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the user profile page.
     */
    public function index(): View
    {
        $user = Auth::user();
        $userDetail = $user->userDetail;
        
        return view('profile.index', compact('user', 'userDetail'));
    }

    /**
     * Update user profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Check if this is just a profile picture upload
        if ($request->hasFile('profile_picture') && !$request->filled('name')) {
            \Log::info('Profile picture upload detected', [
                'has_file' => $request->hasFile('profile_picture'),
                'has_name' => $request->filled('name'),
                'all_inputs' => $request->all()
            ]);
            return $this->updateProfilePicture($request);
        }
        
        // Validate basic user information
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'cin' => 'nullable|string|max:20',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update user basic information
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        // Handle profile picture upload
        $profilePictureUrl = null;
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->userDetail && $user->userDetail->profile_picture_url) {
                $oldPath = str_replace('/storage/', '', $user->userDetail->profile_picture_url);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $profilePictureUrl = '/storage/' . $path;
            
            // Debug: Log the upload
            \Log::info('Profile picture uploaded', [
                'user_id' => $user->id,
                'path' => $path,
                'url' => $profilePictureUrl,
                'full_url' => asset($profilePictureUrl)
            ]);
        }

        // Update or create user details
        $userDetail = $user->userDetail;
        if (!$userDetail) {
            $userDetail = new UserDetail();
            $userDetail->user_id = $user->id;
        }

        $userDetail->fill([
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'address' => $request->address,
            'city' => $request->city,
            'cin' => $request->cin,
        ]);

        if ($profilePictureUrl) {
            $userDetail->profile_picture_url = $profilePictureUrl;
        }

        $userDetail->save();

        return redirect()->route('profile.index')
            ->with('success', 'Profil mis à jour avec succès !');
    }

    /**
     * Update user password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('profile.index')
            ->with('success', 'Mot de passe mis à jour avec succès !');
    }

    /**
     * Upload ID verification documents.
     */
    public function uploadIdDocuments(Request $request)
    {
        $request->validate([
            'id_document_front' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'id_document_back' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();
        $userDetail = $user->userDetail;

        if (!$userDetail) {
            $userDetail = new UserDetail();
            $userDetail->user_id = $user->id;
        }

        // Handle front document upload
        if ($request->hasFile('id_document_front')) {
            $frontPath = $request->file('id_document_front')->store('id-documents', 'public');
            $userDetail->id_document_front = '/storage/' . $frontPath;
        }

        // Handle back document upload
        if ($request->hasFile('id_document_back')) {
            $backPath = $request->file('id_document_back')->store('id-documents', 'public');
            $userDetail->id_document_back = '/storage/' . $backPath;
        }

        $userDetail->id_verification_status = 'pending';
        $userDetail->save();

        return redirect()->route('profile.index')
            ->with('success', 'Documents d\'identité soumis avec succès ! Votre vérification est en cours.');
    }

    /**
     * Update profile picture only.
     */
    private function updateProfilePicture(Request $request)
    {
        $user = Auth::user();
        
        // Validate profile picture
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Delete old profile picture if exists
        if ($user->userDetail && $user->userDetail->profile_picture_url) {
            $oldPath = str_replace('/storage/', '', $user->userDetail->profile_picture_url);
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }
        
        // Upload new profile picture
        $path = $request->file('profile_picture')->store('profile_pictures', 'public');
        $profilePictureUrl = '/storage/' . $path;
        
        // Debug: Log the upload
        \Log::info('Profile picture uploaded', [
            'user_id' => $user->id,
            'path' => $path,
            'url' => $profilePictureUrl,
            'full_url' => asset($profilePictureUrl)
        ]);

        // Update or create user details
        $userDetail = $user->userDetail;
        if (!$userDetail) {
            $userDetail = new UserDetail();
            $userDetail->user_id = $user->id;
        }

        $userDetail->profile_picture_url = $profilePictureUrl;
        $userDetail->save();

        return redirect()->route('profile.index')
            ->with('success', 'Photo de profil mise à jour avec succès !');
    }

    /**
     * Delete profile picture.
     */
    public function deleteProfilePicture()
    {
        $user = Auth::user();
        $userDetail = $user->userDetail;

        if ($userDetail && $userDetail->profile_picture_url) {
            $oldPath = str_replace('/storage/', '', $userDetail->profile_picture_url);
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
            $userDetail->update(['profile_picture_url' => null]);
        }

        return redirect()->route('profile.index')
            ->with('success', 'Photo de profil supprimée avec succès !');
    }
}
