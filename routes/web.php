<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserDashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/profile', function () {
    return view('profile.index');
})->name('profile')->middleware('auth');

Route::get('/settings', function () {
    return view('settings.index');
})->name('settings')->middleware('auth');

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

Route::get('/terms', function () {
    return view('legal.terms');
})->name('terms');

Route::get('/privacy', function () {
    return view('legal.privacy');
})->name('privacy');

// Routes du profil utilisateur (à implémenter plus tard)
Route::prefix('profile')->middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('profile.index');
    })->name('profile.index');
});
