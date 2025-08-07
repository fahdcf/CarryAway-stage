<?php

namespace App\Http\Controllers;

use App\Models\SenderAnnouncement;
use App\Models\SenderAnnouncementDetail;
use App\Models\TravelerAnnouncement;
use App\Models\TravelerAnnouncementDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserDashboardController extends Controller
{
    /**
     * Show the user dashboard with all announcements.
     */
    public function index(): View
    {
        $user = Auth::user();
        
        // Get user's own announcements
        $myTravelerAnnouncements = $user->travelerAnnouncements()
            ->with('details')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $mySenderAnnouncements = $user->senderAnnouncements()
            ->with('details')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get all other users' announcements
        $otherTravelerAnnouncements = TravelerAnnouncement::where('user_id', '!=', $user->id)
            ->where('is_active', true)
            ->where('status', 'active')
            ->with(['user', 'details'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        $otherSenderAnnouncements = SenderAnnouncement::where('user_id', '!=', $user->id)
            ->where('is_active', true)
            ->where('status', 'active')
            ->with(['user', 'details'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.index', compact(
            'myTravelerAnnouncements',
            'mySenderAnnouncements',
            'otherTravelerAnnouncements',
            'otherSenderAnnouncements'
        ));
    }

    /**
     * Show create traveler announcement form.
     */
    public function createTravelerAnnouncement(): View
    {
        return view('dashboard.create-traveler-announcement');
    }

    /**
     * Store a new traveler announcement.
     */
    public function storeTravelerAnnouncement(Request $request)
    {
        $request->validate([
            'departure_city' => 'required|string|max:255',
            'arrival_city' => 'required|string|max:255',
            'departure_date' => 'required|date|after:today',
            'available_kg' => 'required|numeric|min:0.1|max:100',
            'description' => 'nullable|string|max:1000',
            'transport_mode' => 'nullable|string|max:255',
            'estimated_arrival_date' => 'nullable|date|after:departure_date',
            'price_per_kg' => 'nullable|numeric|min:0|max:1000',
            'extra_notes' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();

        // Create traveler announcement
        $announcement = TravelerAnnouncement::create([
            'user_id' => $user->id,
            'departure_city' => $request->departure_city,
            'arrival_city' => $request->arrival_city,
            'departure_date' => $request->departure_date,
            'available_kg' => $request->available_kg,
            'description' => $request->description,
            'status' => 'active',
            'is_active' => true,
        ]);

        // Create announcement details
        TravelerAnnouncementDetail::create([
            'announcement_id' => $announcement->id,
            'transport_mode' => $request->transport_mode,
            'estimated_arrival_date' => $request->estimated_arrival_date,
            'price_per_kg' => $request->price_per_kg,
            'extra_notes' => $request->extra_notes,
        ]);

        return redirect()->route('dashboard.index')
            ->with('success', 'Annonce de voyage créée avec succès !');
    }

    /**
     * Show create sender announcement form.
     */
    public function createSenderAnnouncement(): View
    {
        return view('dashboard.create-sender-announcement');
    }

    /**
     * Store a new sender announcement.
     */
    public function storeSenderAnnouncement(Request $request)
    {
        $request->validate([
            'pickup_city' => 'required|string|max:255',
            'delivery_city' => 'required|string|max:255',
            'preferred_date' => 'required|date|after:today',
            'package_weight' => 'required|numeric|min:0.1|max:100',
            'description' => 'nullable|string|max:1000',
            'item_type' => 'nullable|string|max:255',
            'dimensions' => 'nullable|string|max:255',
            'declared_value' => 'nullable|numeric|min:0|max:100000',
            'additional_info' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();

        // Create sender announcement
        $announcement = SenderAnnouncement::create([
            'user_id' => $user->id,
            'pickup_city' => $request->pickup_city,
            'delivery_city' => $request->delivery_city,
            'preferred_date' => $request->preferred_date,
            'package_weight' => $request->package_weight,
            'description' => $request->description,
            'status' => 'active',
            'is_active' => true,
        ]);

        // Create announcement details
        SenderAnnouncementDetail::create([
            'announcement_id' => $announcement->id,
            'item_type' => $request->item_type,
            'dimensions' => $request->dimensions,
            'declared_value' => $request->declared_value,
            'additional_info' => $request->additional_info,
        ]);

        return redirect()->route('dashboard.index')
            ->with('success', 'Annonce de livraison créée avec succès !');
    }

    /**
     * Show user's own announcements.
     */
    public function myAnnouncements(): View
    {
        $user = Auth::user();
        
        $myTravelerAnnouncements = $user->travelerAnnouncements()
            ->with('details')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $mySenderAnnouncements = $user->senderAnnouncements()
            ->with('details')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.my-announcements', compact(
            'myTravelerAnnouncements',
            'mySenderAnnouncements'
        ));
    }

    /**
     * Show all announcements from other users.
     */
    public function allAnnouncements(): View
    {
        $user = Auth::user();
        
        $otherTravelerAnnouncements = TravelerAnnouncement::where('user_id', '!=', $user->id)
            ->where('is_active', true)
            ->where('status', 'active')
            ->with(['user', 'details'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        $otherSenderAnnouncements = SenderAnnouncement::where('user_id', '!=', $user->id)
            ->where('is_active', true)
            ->where('status', 'active')
            ->with(['user', 'details'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.all-announcements', compact(
            'otherTravelerAnnouncements',
            'otherSenderAnnouncements'
        ));
    }

    /**
     * Toggle announcement status (active/inactive).
     */
    public function toggleAnnouncement(Request $request, $type, $id)
    {
        $user = Auth::user();
        
        if ($type === 'traveler') {
            $announcement = $user->travelerAnnouncements()->findOrFail($id);
        } else {
            $announcement = $user->senderAnnouncements()->findOrFail($id);
        }

        $announcement->update([
            'is_active' => !$announcement->is_active
        ]);

        $status = $announcement->is_active ? 'activée' : 'désactivée';
        return back()->with('success', "Annonce $status avec succès !");
    }

    /**
     * Delete an announcement.
     */
    public function deleteAnnouncement(Request $request, $type, $id)
    {
        $user = Auth::user();
        
        if ($type === 'traveler') {
            $announcement = $user->travelerAnnouncements()->findOrFail($id);
        } else {
            $announcement = $user->senderAnnouncements()->findOrFail($id);
        }

        $announcement->delete();

        return back()->with('success', 'Annonce supprimée avec succès !');
    }
}
