<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserDashboardController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AIController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\MessageController;

// Page d'accueil
Route::get('/', [HomeController::class, 'index'])->name('home');

// Routes pour les annonces (à implémenter plus tard)
Route::prefix('announcements')->group(function () {
    // Annonces d'expéditeurs
    Route::get('/sender/create', function () {
        return view('announcements.sender.create');
    })->name('announcements.sender.create');

    // Annonces de voyageurs
    Route::get('/traveler/create', function () {
        return view('announcements.traveler.create');
    })->name('announcements.traveler.create');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// User dashboard routes
Route::middleware(['auth', 'approved.user'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/my-announcements', [UserDashboardController::class, 'myAnnouncements'])->name('dashboard.my-announcements');
    Route::get('/dashboard/all-announcements', [UserDashboardController::class, 'allAnnouncements'])->name('dashboard.all-announcements');

    // Create announcements
    Route::get('/dashboard/create-traveler-announcement', [UserDashboardController::class, 'createTravelerAnnouncement'])->name('dashboard.create-traveler-announcement');
    Route::post('/dashboard/store-traveler-announcement', [UserDashboardController::class, 'storeTravelerAnnouncement'])->name('dashboard.store-traveler-announcement');
    Route::get('/dashboard/create-sender-announcement', [UserDashboardController::class, 'createSenderAnnouncement'])->name('dashboard.create-sender-announcement');
    Route::post('/dashboard/store-sender-announcement', [UserDashboardController::class, 'storeSenderAnnouncement'])->name('dashboard.store-sender-announcement');

    // Edit announcements
    Route::get('/dashboard/edit-traveler-announcement/{id}', [UserDashboardController::class, 'editTravelerAnnouncement'])->name('dashboard.edit-traveler-announcement');
    Route::post('/dashboard/update-traveler-announcement/{id}', [UserDashboardController::class, 'updateTravelerAnnouncement'])->name('dashboard.update-traveler-announcement');
    Route::get('/dashboard/edit-sender-announcement/{id}', [UserDashboardController::class, 'editSenderAnnouncement'])->name('dashboard.edit-sender-announcement');
    Route::post('/dashboard/update-sender-announcement/{id}', [UserDashboardController::class, 'updateSenderAnnouncement'])->name('dashboard.update-sender-announcement');

    // Manage announcements
    Route::post('/dashboard/toggle-announcement/{type}/{id}', [UserDashboardController::class, 'toggleAnnouncement'])->name('dashboard.toggle-announcement');
    Route::delete('/dashboard/delete-announcement/{type}/{id}', [UserDashboardController::class, 'deleteAnnouncement'])->name('dashboard.delete-announcement');
});



// Legacy dashboard route - redirect based on role (commented out due to conflict)
// Route::get('/dashboard', function () {
//     if (Auth::user()->isAdmin()) {
//         return redirect()->route('admin.dashboard');
//     }
// 
//     // For regular users, redirect to new dashboard
//     return redirect()->route('dashboard.index');
// })->name('dashboard')->middleware('auth');

// Profile routes
Route::middleware(['auth', 'approved.user'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::post('/profile/upload-id-documents', [ProfileController::class, 'uploadIdDocuments'])->name('profile.upload-id-documents');
    Route::delete('/profile/delete-picture', [ProfileController::class, 'deleteProfilePicture'])->name('profile.delete-picture');
});

Route::get('/settings', function () {
    return view('settings.index');
})->name('settings.index')->middleware('auth');

Route::get('/messages', [MessageController::class, 'index'])->name('messages.index')->middleware('auth');
Route::get('/messages/{chatId}', [MessageController::class, 'show'])->name('messages.show')->middleware('auth');
Route::post('/messages/{chatId}', [MessageController::class, 'store'])->name('messages.store')->middleware('auth');
Route::get('/messages/create-chat/{requestId}', [MessageController::class, 'createChat'])->name('messages.create-chat')->middleware('auth');

Route::get('/requests', [UserDashboardController::class, 'requests'])->name('requests.index')->middleware('auth');
Route::post('/requests/create', [UserDashboardController::class, 'createRequest'])->name('requests.create')->middleware('auth');
Route::post('/requests/create-sender', [UserDashboardController::class, 'createSenderRequest'])->name('requests.create-sender')->middleware('auth');
Route::post('/requests/request-package-form', [UserDashboardController::class, 'requestPackageForm'])->name('requests.request-package-form')->middleware('auth');
Route::post('/requests/submit-package-form', [UserDashboardController::class, 'submitPackageForm'])->name('requests.submit-package-form')->middleware('auth');
Route::post('/requests/request-traveler-form', [UserDashboardController::class, 'requestTravelerForm'])->name('requests.request-traveler-form')->middleware('auth');
Route::post('/requests/submit-traveler-form', [UserDashboardController::class, 'submitTravelerForm'])->name('requests.submit-traveler-form')->middleware('auth');
Route::get('/requests/{id}/progress', [App\Http\Controllers\RequestProgressController::class, 'show'])->name('requests.progress')->middleware('auth');
Route::post('/requests/{id}/progress', [App\Http\Controllers\RequestProgressController::class, 'update'])->name('requests.progress.update')->middleware('auth');
Route::delete('/requests', [UserDashboardController::class, 'deleteRequest'])->name('requests.delete')->middleware('auth');
Route::post('/requests/accept', [UserDashboardController::class, 'acceptRequest'])->name('requests.accept')->middleware('auth');
Route::post('/requests/reject', [UserDashboardController::class, 'rejectRequest'])->name('requests.reject')->middleware('auth');

// Verification routes
Route::post('/verification/meeting-code', [App\Http\Controllers\VerificationController::class, 'verifyMeetingCode'])->name('verification.meeting-code')->middleware('auth');
Route::post('/verification/delivery-code', [App\Http\Controllers\VerificationController::class, 'verifyDeliveryCode'])->name('verification.delivery-code')->middleware('auth');
Route::post('/verification/mark-completed', [App\Http\Controllers\VerificationController::class, 'markAsCompleted'])->name('verification.mark-completed')->middleware('auth');
Route::get('/verification/codes/{request_id}', [App\Http\Controllers\VerificationController::class, 'getVerificationCodes'])->name('verification.codes')->middleware('auth');

// Notification routes
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notifications/unread-count', [App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::get('/notifications/latest', [App\Http\Controllers\NotificationController::class, 'getLatest'])->name('notifications.latest');
});

// Public profile routes
Route::get('/user/{userId}', [App\Http\Controllers\PublicProfileController::class, 'show'])->name('profiles.show');
Route::middleware('auth')->group(function () {
    Route::post('/user/{userId}/review', [App\Http\Controllers\PublicProfileController::class, 'storeReview'])->name('profiles.review.store');
    Route::put('/user/{userId}/review', [App\Http\Controllers\PublicProfileController::class, 'updateReview'])->name('profiles.review.update');
    Route::delete('/user/{userId}/review', [App\Http\Controllers\PublicProfileController::class, 'deleteReview'])->name('profiles.review.delete');
});

// AI helper routes (proxy Gemini)
Route::post('/api/ai/suggest-declared-value', [AIController::class, 'suggestDeclaredValue'])
    ->name('ai.suggest-declared-value')
    ->middleware('auth');
Route::post('/api/ai/suggest-price-per-kg', [AIController::class, 'suggestPricePerKg'])
    ->name('ai.suggest-price-per-kg')
    ->middleware('auth');

// Debug page to run a detailed test of the AI value
Route::get('/debug/ai-sender', [AIController::class, 'debugSender'])
    ->name('debug.ai-sender')
    ->middleware('auth');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users/{id}', [AdminController::class, 'showUser'])->name('users.show');
    Route::post('/users/{id}/approve', [AdminController::class, 'approveUser'])->name('users.approve');
    Route::post('/users/{id}/reject', [AdminController::class, 'rejectUser'])->name('users.reject');
    Route::get('/users', [AdminController::class, 'allUsers'])->name('users.all');
    Route::post('/users/{id}/suspend', [AdminController::class, 'suspendUser'])->name('users.suspend');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::post('/users/{id}/update', [AdminController::class, 'updateUser'])->name('users.update');
    Route::post('/users/{id}/activate', [AdminController::class, 'activateUser'])->name('users.activate');
    Route::post('/users/{id}/deactivate', [AdminController::class, 'deactivateUser'])->name('users.deactivate');
    Route::post('/users/{id}/reactivate', [AdminController::class, 'reactivateUser'])->name('users.reactivate');
});

// Media proxy for public disk (robust on Windows/Laragon)
Route::get('/media/{path}', function (string $path) {
    $normalized = ltrim(str_replace('\\', '/', $path), '/');

    // Strip leading prefixes if present
    if (str_starts_with($normalized, 'public/')) {
        $normalized = substr($normalized, 7);
    } elseif (str_starts_with($normalized, 'storage/')) {
        $normalized = substr($normalized, 8);
    }

    $candidates = [];
    $candidates[] = $normalized; // as-is in public disk

    // Try common directories by basename
    $basename = basename($normalized);
    if ($basename && $basename !== $normalized) {
        $candidates[] = 'announcement-pictures/' . $basename;
        $candidates[] = 'profile_pictures/' . $basename;
        $candidates[] = 'id_documents/' . $basename;
    }

    foreach ($candidates as $candidate) {
        if (Storage::disk('public')->exists($candidate)) {
            return Storage::disk('public')->response($candidate);
        }
    }

    // Log for diagnostics
    \Log::warning('Media not found', [
        'requested_path' => $path,
        'normalized' => $normalized,
        'candidates' => $candidates,
    ]);

    abort(404);
})->where('path', '.*')->name('media');

Route::get('/shipments/create', function () {
    return view('shipments.create');
})->name('shipments.create')->middleware('auth');

Route::get('/travels/create', function () {
    return view('travels.create');
})->name('travels.create')->middleware('auth');

Route::get('/help', function () {
    return view('help.index');
})->name('help');

Route::get('/contact', function () {
    return view('contact.index');
})->name('contact');

Route::get('/about', function () {
    return view('about.index');
})->name('about');

// Test route for debugging profile pictures
Route::get('/test-profile-picture', function () {
    $user = \App\Models\User::first();
    if ($user && $user->userDetail) {
        return response()->json([
            'user_id' => $user->id,
            'profile_picture_url' => $user->userDetail->profile_picture_url,
            'has_profile_picture' => $user->userDetail->hasProfilePicture(),
            'storage_path' => storage_path('app/public'),
            'public_path' => public_path('storage'),
        ]);
    }
    return response()->json(['error' => 'No user found']);
})->middleware('auth');

Route::get('/terms', function () {
    return view('legal.terms');
})->name('terms');

Route::get('/privacy', function () {
    return view('legal.privacy');
})->name('privacy');

// Routes du profil utilisateur (à implémenter plus tard)
// Note: Profile route is now handled by the main /profile route above
