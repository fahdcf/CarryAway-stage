<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Review;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PublicProfileController extends Controller
{
    /**
     * Show a user's public profile.
     */
    public function show(string $userId): View
    {
        $user = User::with(['userDetail', 'receivedReviews.reviewer', 'travelerAnnouncements', 'senderAnnouncements'])
            ->where('id', $userId)
            ->where('status', 'active')
            ->firstOrFail();

        // Calculate average rating and total reviews
        $reviews = $user->receivedReviews;
        $averageRating = $reviews->avg('rating') ?? 0;
        $totalReviews = $reviews->count();
        
        // Get rating distribution (1-5 stars)
        $ratingDistribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $ratingDistribution[$i] = $reviews->where('rating', $i)->count();
        }

        // Get recent reviews (last 5)
        $recentReviews = $reviews->sortByDesc('created_at')->take(5);

        // Get user statistics
        $totalAnnouncements = $user->travelerAnnouncements->count() + $user->senderAnnouncements->count();
        $memberSince = $user->created_at->format('F Y');

        // Check if current user has already reviewed this user
        $currentUser = Auth::user();
        $hasReviewed = false;
        $userReview = null;
        
        if ($currentUser && $currentUser->id !== $user->id) {
            $userReview = Review::where('reviewer_id', $currentUser->id)
                ->where('reviewed_user_id', $user->id)
                ->first();
            $hasReviewed = $userReview !== null;
        }

        return view('profiles.public', compact(
            'user',
            'averageRating',
            'totalReviews',
            'ratingDistribution',
            'recentReviews',
            'totalAnnouncements',
            'memberSince',
            'hasReviewed',
            'userReview'
        ));
    }

    /**
     * Store a new review for a user.
     */
    public function storeReview(Request $request, string $userId): RedirectResponse
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $reviewedUser = User::findOrFail($userId);
        $reviewer = Auth::user();

        // Check if user is reviewing themselves
        if ($reviewer->id === $reviewedUser->id) {
            return back()->with('error', 'Vous ne pouvez pas vous évaluer vous-même.');
        }

        // Check if user has already reviewed this user
        $existingReview = Review::where('reviewer_id', $reviewer->id)
            ->where('reviewed_user_id', $userId)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Vous avez déjà évalué cet utilisateur.');
        }

        // Create the review
        Review::create([
            'reviewer_id' => $reviewer->id,
            'reviewed_user_id' => $userId,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Votre évaluation a été enregistrée avec succès !');
    }

    /**
     * Update an existing review.
     */
    public function updateReview(Request $request, string $userId): RedirectResponse
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $reviewer = Auth::user();
        
        $review = Review::where('reviewer_id', $reviewer->id)
            ->where('reviewed_user_id', $userId)
            ->firstOrFail();

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Votre évaluation a été mise à jour avec succès !');
    }

    /**
     * Delete a review.
     */
    public function deleteReview(string $userId): RedirectResponse
    {
        $reviewer = Auth::user();
        
        $review = Review::where('reviewer_id', $reviewer->id)
            ->where('reviewed_user_id', $userId)
            ->firstOrFail();

        $review->delete();

        return back()->with('success', 'Votre évaluation a été supprimée.');
    }
}
