<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Check if user can login (approved and not suspended)
            if (!$user->canLogin()) {
                Auth::logout();

                if ($user->isPending()) {
                    return back()->withErrors([
                        'email' => 'Votre compte est en attente d\'approbation par un administrateur.',
                    ]);
                } elseif ($user->isRejected()) {
                    return back()->withErrors([
                        'email' => 'Votre compte a été rejeté. Raison: ' . $user->rejection_reason,
                    ]);
                } elseif ($user->isSuspended()) {
                    return back()->withErrors([
                        'email' => 'Votre compte a été suspendu.',
                    ]);
                }
            }

            // Redirect based on role
            if ($user->isAdmin()) {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Les informations d\'identification fournies ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'birth_date' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:male,female'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'cin' => ['required', 'string', 'max:20', 'unique:user_details'],
            'id_document_front' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'id_document_back' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Create user with pending status
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'user',
                'status' => 'pending',
            ]);

            // Handle file uploads
            $idFrontPath = $request->file('id_document_front')->store('id_documents', 'public');
            $idBackPath = $request->file('id_document_back')->store('id_documents', 'public');
            $profilePicturePath = null;

            if ($request->hasFile('profile_picture')) {
                $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            }

            // Create user details
            UserDetail::create([
                'user_id' => $user->id,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'address' => $request->address,
                'city' => $request->city,
                'cin' => $request->cin,
                'profile_picture_url' => $profilePicturePath,
                'id_document_front' => $idFrontPath,
                'id_document_back' => $idBackPath,
                'id_verification_status' => 'pending',
                'verified' => false,
            ]);

            return redirect()->route('login')->with('success',
                'Votre compte a été créé avec succès. Il est en attente d\'approbation par un administrateur. Vous recevrez un email une fois votre compte approuvé.'
            );

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la création du compte.'])->withInput();
        }
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
