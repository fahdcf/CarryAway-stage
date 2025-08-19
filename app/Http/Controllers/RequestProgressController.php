<?php

namespace App\Http\Controllers;

use App\Models\UserRequest;
use App\Models\RequestProgress;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class RequestProgressController extends Controller
{
    /**
     * Show the progress page for a specific request.
     */
    public function show($requestId): View
    {
        $user = Auth::user();
        $userRequest = UserRequest::with(['progress', 'initiator', 'announcementOwner', 'travelerAnnouncement', 'senderAnnouncement', 'package'])
            ->findOrFail($requestId);

        // Check if user is involved in this request
        if ($user->id !== $userRequest->initiator_user_id && $user->id !== $userRequest->announcement_owner_id) {
            abort(403);
        }

        $isOwner = $user->id === $userRequest->announcement_owner_id;
        $announcement = $userRequest->travelerAnnouncement ?? $userRequest->senderAnnouncement;

        return view('requests.progress', compact('userRequest', 'isOwner', 'announcement'));
    }

    /**
     * Update the progress of a request (owner only for traveler announcements, requester only for sender announcements).
     */
    public function update(Request $request, $requestId): RedirectResponse
    {
        $user = Auth::user();
        $userRequest = UserRequest::findOrFail($requestId);

        // Check if user is involved in this request
        if ($user->id !== $userRequest->initiator_user_id && $user->id !== $userRequest->announcement_owner_id) {
            abort(403);
        }

        // Determine announcement type and check permissions
        $announcementType = null;
        if ($userRequest->travelerAnnouncement) {
            $announcementType = 'travel';
        } elseif ($userRequest->senderAnnouncement) {
            $announcementType = 'sender';
        }

        // For sender announcements, only the requester (traveler) can update progress
        // For traveler announcements, only the owner can update progress
        if ($announcementType === 'sender' && $user->id !== $userRequest->initiator_user_id) {
            abort(403, 'Seul le voyageur peut mettre à jour la progression pour les annonces de livraison.');
        } elseif ($announcementType === 'travel' && $user->id !== $userRequest->announcement_owner_id) {
            abort(403, 'Seul le propriétaire de l\'annonce peut mettre à jour la progression.');
        }

        $request->validate([
            'stage' => 'required|string|in:reception,en_transit,arrive_destination,livraison',
            'status' => 'required|string|in:en_cours,termine,retarde',
            'notes' => 'nullable|string|max:1000',
            'delivery_code' => 'required_if:stage,livraison|required_if:status,termine|string|max:8',
        ]);

        // If stage is "livraison" and status is "termine", verify delivery code first
        if ($request->stage === 'livraison' && $request->status === 'termine') {
            $deliveryCode = $request->input('delivery_code');
            
            if (!$deliveryCode) {
                return back()->withErrors(['delivery_code' => 'Le code de livraison est requis pour marquer la livraison comme terminée.']);
            }

            // Verify the delivery code
            if (!$userRequest->verifyDeliveryCode($deliveryCode)) {
                return back()->withErrors(['delivery_code' => 'Code de livraison incorrect. Veuillez vérifier le code fourni par l\'expéditeur.']);
            }
        }

        // Create progress update
        RequestProgress::create([
            'request_id' => $requestId,
            'stage' => $request->stage,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        // If stage is "livraison" and status is "termine", mark request as completed
        if ($request->stage === 'livraison' && $request->status === 'termine') {
            $userRequest->update(['status' => 'completed']);
        }

        return back()->with('success', 'Progression mise à jour avec succès !');
    }

    /**
     * Get predefined stages for progress updates.
     */
    public static function getStages(): array
    {
        return [
            'reception' => 'Réception',
            'en_transit' => 'En transit',
            'arrive_destination' => 'Arrivé à destination',
            'livraison' => 'Livraison',
        ];
    }

    /**
     * Get predefined statuses for progress updates.
     */
    public static function getStatuses(): array
    {
        return [
            'en_cours' => 'En cours',
            'termine' => 'Terminé',
            'retarde' => 'Retardé',
        ];
    }
}
