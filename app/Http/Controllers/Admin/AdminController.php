<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDetail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Deactivate a user account.
     */
    public function deactivateUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->isAdmin()) {
            return back()->withErrors(['error' => 'Impossible de désactiver un administrateur.']);
        }
        $user->update(['status' => 'suspended']);
        return back()->with('success', 'Utilisateur ' . $user->name . ' désactivé.');
    }

    /**
     * Activate a user account.
     */
    public function activateUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'active']);
        return back()->with('success', 'Utilisateur ' . $user->name . ' activé.');
    }
    /**
     * Show admin dashboard.
     */
    public function dashboard()
    {
        $pendingUsers = User::where('status', 'pending')
            ->with('userDetail')
            ->orderBy('created_at', 'desc')
            ->get();

        $allUsers = User::where('role', 'user')
            ->whereNot('status','pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalUsers = User::where('role', 'user')->count();
        $pendingCount = User::where('status', 'pending')->count();
        $approvedCount = User::where('status', 'active')->count();
        $rejectedCount = User::where('status', 'rejected')->count();

        return view('admin.dashboard', compact(
            'pendingUsers',
            'allUsers',
            'totalUsers',
            'pendingCount',
            'approvedCount',
            'rejectedCount'
        ));
    }

    /**
     * Show user details for approval.
     */
    public function showUser($id)
    {
        $user = User::with('userDetail')->findOrFail($id);

        // Only show pending users or allow admins to view any user
        if (!Auth::user()->isAdmin() || ($user->status !== 'pending' && !request()->has('view'))) {
            abort(403);
        }

        return view('admin.user-details', compact('user'));
    }

    /**
     * Approve a user account.
     */
    public function approveUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->status !== 'pending') {
            return back()->withErrors(['error' => 'Cet utilisateur n\'est pas en attente d\'approbation.']);
        }

        $user->update([
            'status' => 'active',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'rejection_reason' => null,
        ]);

        // Update user detail verification status
        if ($user->userDetail) {
            $user->userDetail->update([
                'id_verification_status' => 'verified',
                'id_verified_at' => now(),
                'verified' => true,
            ]);
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Utilisateur ' . $user->name . ' approuvé avec succès.');
    }

    /**
     * Reject a user account.
     */
    public function rejectUser(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ]);

        $user = User::findOrFail($id);

        if ($user->status !== 'pending') {
            return back()->withErrors(['error' => 'Cet utilisateur n\'est pas en attente d\'approbation.']);
        }

        $user->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_by' => Auth::id(),
        ]);

        // Update user detail verification status
        if ($user->userDetail) {
            $user->userDetail->update([
                'id_verification_status' => 'rejected',
                'id_verification_notes' => $request->rejection_reason,
            ]);
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Utilisateur ' . $user->name . ' rejeté.');
    }

    /**
     * Show all users (approved, rejected, suspended).
     */
    public function allUsers(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = $request->get('search');

        $query = User::where('role', 'user')->with('userDetail');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.all-users', compact('users', 'status', 'search'));
    }

    /**
     * Suspend a user account.
     */
    public function suspendUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->isAdmin()) {
            return back()->withErrors(['error' => 'Impossible de suspendre un administrateur.']);
        }

        $user->update(['status' => 'suspended']);

        return back()->with('success', 'Utilisateur ' . $user->name . ' suspendu.');
    }

    /**
     * Reactivate a suspended user account.
     */
    public function reactivateUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->status === 'suspended') {
            $user->update(['status' => 'active']);
            return back()->with('success', 'Utilisateur ' . $user->name . ' réactivé.');
        }

        return back()->withErrors(['error' => 'Cet utilisateur n\'est pas suspendu.']);
    }

    /**
     * Show the edit form for a user.
     */
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit-user', compact('user'));
    }

    /**
     * Update user info from admin dashboard.
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone
        ]);
        if($user->userDetail){

            $details=$user->userDetail;
            $details->city=$request->city;
            $details->save();
        }
        else{
             // Create user details
            UserDetail::create([
                'user_id' => $user->id,
                'city'=>$request->city,
               
            ]);

        }


        return redirect()->route('admin.dashboard')->with('success', 'Informations utilisateur mises à jour.');
    }
}
