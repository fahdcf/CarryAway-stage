<?php

namespace App\Http\Controllers;

use App\Models\UserRequest;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class VerificationController extends Controller
{
    /**
     * Verify meeting code
     */
    public function verifyMeetingCode(Request $request): JsonResponse
    {
        $request->validate([
            'request_id' => 'required|exists:user_requests,id',
            'meeting_code' => 'required|string|max:8',
        ]);

        $user = Auth::user();
        $userRequest = UserRequest::findOrFail($request->request_id);

        // Check if user is involved in this request
        if ($user->id !== $userRequest->initiator_user_id && $user->id !== $userRequest->announcement_owner_id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à vérifier ce code.'
            ], 403);
        }

        // Check if meeting code is already verified
        if ($userRequest->isMeetingVerified()) {
            return response()->json([
                'success' => false,
                'message' => 'Le code de rencontre a déjà été vérifié.'
            ], 400);
        }

        // Verify the meeting code
        if ($userRequest->verifyMeetingCode($request->meeting_code)) {
            // Send notification to the other user
            $otherUserId = $user->id === $userRequest->initiator_user_id 
                ? $userRequest->announcement_owner_id 
                : $userRequest->initiator_user_id;
            
            $otherUser = \App\Models\User::find($otherUserId);
            if ($otherUser) {
                Notification::createMeetingVerifiedNotification($userRequest, $otherUser);
            }

            return response()->json([
                'success' => true,
                'message' => 'Code de rencontre vérifié avec succès !',
                'meeting_verified' => true,
                'can_complete' => $userRequest->canBeCompleted()
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Code de rencontre incorrect.'
        ], 400);
    }

    /**
     * Verify delivery code
     */
    public function verifyDeliveryCode(Request $request): JsonResponse
    {
        $request->validate([
            'request_id' => 'required|exists:user_requests,id',
            'delivery_code' => 'required|string|max:8',
        ]);

        $user = Auth::user();
        $userRequest = UserRequest::findOrFail($request->request_id);

        // Check if user is the announcement owner (only they have the delivery code)
        if ($user->id !== $userRequest->announcement_owner_id) {
            return response()->json([
                'success' => false,
                'message' => 'Seul le propriétaire de l\'annonce peut vérifier le code de livraison.'
            ], 403);
        }

        // Check if delivery code is already verified
        if ($userRequest->isDeliveryVerified()) {
            return response()->json([
                'success' => false,
                'message' => 'Le code de livraison a déjà été vérifié.'
            ], 400);
        }

        // Verify the delivery code
        if ($userRequest->verifyDeliveryCode($request->delivery_code)) {
            // Send notification to the requester
            $requester = $userRequest->initiator;
            if ($requester) {
                Notification::createDeliveryVerifiedNotification($userRequest, $requester);
            }

            return response()->json([
                'success' => true,
                'message' => 'Code de livraison vérifié avec succès !',
                'delivery_verified' => true,
                'can_complete' => $userRequest->canBeCompleted()
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Code de livraison incorrect.'
        ], 400);
    }

    /**
     * Mark request as completed
     */
    public function markAsCompleted(Request $request): JsonResponse
    {
        $request->validate([
            'request_id' => 'required|exists:user_requests,id',
        ]);

        $user = Auth::user();
        $userRequest = UserRequest::findOrFail($request->request_id);

        // Check if user is involved in this request
        if ($user->id !== $userRequest->initiator_user_id && $user->id !== $userRequest->announcement_owner_id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à modifier cette demande.'
            ], 403);
        }

        // Check if request can be completed
        if (!$userRequest->canBeCompleted()) {
            return response()->json([
                'success' => false,
                'message' => 'La demande ne peut pas encore être marquée comme terminée. Vérifiez d\'abord les codes requis.'
            ], 400);
        }

        // Update status to completed
        $userRequest->update(['status' => 'completed']);

        return response()->json([
            'success' => true,
            'message' => 'Demande marquée comme terminée avec succès !',
            'status' => 'completed'
        ]);
    }

    /**
     * Get verification codes for a request
     */
    public function getVerificationCodes(Request $request): JsonResponse
    {
        $request->validate([
            'request_id' => 'required|exists:user_requests,id',
        ]);

        $user = Auth::user();
        $userRequest = UserRequest::findOrFail($request->request_id);

        // Check if user is involved in this request
        if ($user->id !== $userRequest->initiator_user_id && $user->id !== $userRequest->announcement_owner_id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à voir ces codes.'
            ], 403);
        }

        $allCodes = $userRequest->getVerificationCodes();
        $isAnnouncementOwner = ($user->id === $userRequest->announcement_owner_id);
        
        if ($userRequest->sender_announcement_id) {
            // Sender announcement: Sender (owner) gets 2 codes, requester gets 1 code
            if ($isAnnouncementOwner) {
                // Sender (owner) gets both meeting and delivery codes
                $codes = $allCodes;
            } else {
                // Requester gets only meeting code
                $codes = [
                    'meeting_code' => $allCodes['meeting_code'],
                    'meeting_verified' => $allCodes['meeting_verified'],
                ];
            }
        } else {
            // Traveler announcement: Traveler (owner) gets 1 code, requester (sender) gets 2 codes
            if ($isAnnouncementOwner) {
                // Traveler (owner) gets only meeting code
                $codes = [
                    'meeting_code' => $allCodes['meeting_code'],
                    'meeting_verified' => $allCodes['meeting_verified'],
                ];
            } else {
                // Requester (sender) gets both meeting and delivery codes
                $codes = $allCodes;
            }
        }

        return response()->json([
            'success' => true,
            'codes' => $codes,
            'can_complete' => $userRequest->canBeCompleted()
        ]);
    }
}
