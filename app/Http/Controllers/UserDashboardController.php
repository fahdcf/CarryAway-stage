<?php

namespace App\Http\Controllers;

use App\Models\SenderAnnouncement;
use App\Models\SenderAnnouncementDetail;
use App\Models\TravelerAnnouncement;
use App\Models\TravelerAnnouncementDetail;
use App\Models\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\Chat; // Added this import for the new chat creation
use App\Models\Message; // Added this import for the new message creation
use App\Models\Package; // Added this import for the new package creation

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
        $otherTravelerAnnouncements = TravelerAnnouncement::query()
            ->where('is_active', true)
            ->where('status', 'active')
            ->with(['user', 'details'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        $otherSenderAnnouncements = SenderAnnouncement::query()
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
            'departure_time' => [
                'nullable',
                function ($attribute, $value, $fail) use ($request) {
                    // Skip validation if value is null, empty string, or just whitespace
                    if (!$value || $value === '' || trim($value) === '') {
                        return;
                    }
                    
                    // Handle different time formats (H:i, H:i:s, etc.)
                    $timeValue = trim($value);
                    
                    // Check if it's a valid time format (H:i or H:i:s)
                    if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/', $timeValue)) {
                        $fail('L\'heure de départ doit être au format HH:MM.');
                        return;
                    }
                    
                    $arrivalDate = $request->input('estimated_arrival_date');
                    $departureDate = $request->input('departure_date');
                    
                    // If arrival date is same as departure date, departure time is required
                    if ($arrivalDate && $departureDate && $arrivalDate === $departureDate && !$value) {
                        $fail('L\'heure de départ est requise lorsque l\'arrivée est prévue le même jour.');
                    }
                }
            ],
            'available_kg' => 'required|numeric|min:0.1|max:100',
            'description' => 'nullable|string|max:1000',
            'transport_mode' => 'nullable|string|max:255',
            'estimated_arrival_date' => 'nullable|date|after_or_equal:departure_date',
            'estimated_arrival_time' => [
                'nullable',
                function ($attribute, $value, $fail) use ($request) {
                    // Skip validation if value is null, empty string, or just whitespace
                    if (!$value || $value === '' || trim($value) === '') {
                        return;
                    }
                    
                    // Handle different time formats (H:i, H:i:s, etc.)
                    $timeValue = trim($value);
                    
                    // Check if it's a valid time format (H:i or H:i:s)
                    if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/', $timeValue)) {
                        $fail('L\'heure d\'arrivée doit être au format HH:MM.');
                        return;
                    }
                    
                    $arrivalDate = $request->input('estimated_arrival_date');
                    $departureDate = $request->input('departure_date');
                    $departureTime = $request->input('departure_time');
                    
                    // If arrival date is same as departure date, both times are required
                    if ($arrivalDate && $departureDate && $arrivalDate === $departureDate) {
                        if (!$departureTime) {
                            $fail('L\'heure de départ est requise lorsque l\'arrivée est prévue le même jour.');
                        }
                        if (!$value) {
                            $fail('L\'heure d\'arrivée est requise lorsque l\'arrivée est prévue le même jour que le départ.');
                        }
                        
                        // If both times provided, arrival must be after departure
                        if ($departureTime && $value) {
                            $depDateTime = new \DateTime($departureDate . ' ' . $departureTime);
                            $arrDateTime = new \DateTime($arrivalDate . ' ' . $value);
                            
                            if ($arrDateTime <= $depDateTime) {
                                $fail('L\'heure d\'arrivée doit être postérieure à l\'heure de départ pour une livraison le même jour.');
                            }
                        }
                    }
                }
            ],
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
            'departure_time' => $request->departure_time,
            'estimated_arrival_date' => $request->estimated_arrival_date,
            'estimated_arrival_time' => $request->estimated_arrival_time,
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
     * Show edit traveler announcement form.
     */
    public function editTravelerAnnouncement($id): View
    {
        $user = Auth::user();
        $announcement = TravelerAnnouncement::where('user_id', $user->id)
            ->with('details')
            ->findOrFail($id);

        return view('dashboard.create-traveler-announcement', compact('announcement'));
    }

    /**
     * Update an existing traveler announcement.
     */
    public function updateTravelerAnnouncement(Request $request, $id)
    {
        $user = Auth::user();
        $announcement = TravelerAnnouncement::where('user_id', $user->id)->findOrFail($id);

        $request->validate([
            'departure_city' => 'required|string|max:255',
            'arrival_city' => 'required|string|max:255',
            'departure_date' => 'required|date|after:today',
            'departure_time' => [
                'nullable',
                function ($attribute, $value, $fail) use ($request) {
                    // Skip validation if value is null, empty string, or just whitespace
                    if (!$value || $value === '' || trim($value) === '') {
                        return;
                    }
                    
                    // Handle different time formats (H:i, H:i:s, etc.)
                    $timeValue = trim($value);
                    
                    // Check if it's a valid time format (H:i or H:i:s)
                    if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/', $timeValue)) {
                        $fail('L\'heure de départ doit être au format HH:MM.');
                        return;
                    }
                    
                    $arrivalDate = $request->input('estimated_arrival_date');
                    $departureDate = $request->input('departure_date');
                    
                    // If arrival date is same as departure date, departure time is required
                    if ($arrivalDate && $departureDate && $arrivalDate === $departureDate && !$value) {
                        $fail('L\'heure de départ est requise lorsque l\'arrivée est prévue le même jour.');
                    }
                }
            ],
            'available_kg' => 'required|numeric|min:0.1|max:100',
            'description' => 'nullable|string|max:1000',
            'transport_mode' => 'nullable|string|max:255',
            'estimated_arrival_date' => 'nullable|date|after_or_equal:departure_date',
            'estimated_arrival_time' => [
                'nullable',
                function ($attribute, $value, $fail) use ($request) {
                    // Skip validation if value is null, empty string, or just whitespace
                    if (!$value || $value === '' || trim($value) === '') {
                        return;
                    }
                    
                    // Handle different time formats (H:i, H:i:s, etc.)
                    $timeValue = trim($value);
                    
                    // Check if it's a valid time format (H:i or H:i:s)
                    if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/', $timeValue)) {
                        $fail('L\'heure d\'arrivée doit être au format HH:MM.');
                        return;
                    }
                    
                    $arrivalDate = $request->input('estimated_arrival_date');
                    $departureDate = $request->input('departure_date');
                    $departureTime = $request->input('departure_time');
                    
                    // If arrival date is same as departure date, both times are required
                    if ($arrivalDate && $departureDate && $arrivalDate === $departureDate) {
                        if (!$departureTime) {
                            $fail('L\'heure de départ est requise lorsque l\'arrivée est prévue le même jour.');
                        }
                        if (!$value) {
                            $fail('L\'heure d\'arrivée est requise lorsque l\'arrivée est prévue le même jour que le départ.');
                        }
                        
                        // If both times provided, arrival must be after departure
                        if ($departureTime && $value) {
                            $depDateTime = new \DateTime($departureDate . ' ' . $departureTime);
                            $arrDateTime = new \DateTime($arrivalDate . ' ' . $value);
                            
                            if ($arrDateTime <= $depDateTime) {
                                $fail('L\'heure d\'arrivée doit être postérieure à l\'heure de départ pour une livraison le même jour.');
                            }
                        }
                    }
                }
            ],
            'price_per_kg' => 'nullable|numeric|min:0|max:1000',
            'extra_notes' => 'nullable|string|max:1000',
        ]);

        // Update announcement
        $announcement->update([
            'departure_city' => $request->departure_city,
            'arrival_city' => $request->arrival_city,
            'departure_date' => $request->departure_date,
            'available_kg' => $request->available_kg,
            'description' => $request->description,
        ]);

        // Update announcement details
        $announcement->details->update([
            'transport_mode' => $request->transport_mode,
            'departure_time' => $request->departure_time,
            'estimated_arrival_date' => $request->estimated_arrival_date,
            'estimated_arrival_time' => $request->estimated_arrival_time,
            'price_per_kg' => $request->price_per_kg,
            'extra_notes' => $request->extra_notes,
        ]);

        return redirect()->route('dashboard.my-announcements')
            ->with('success', 'Annonce de voyage mise à jour avec succès !');
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
            'pictures.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        $user = Auth::user();

        // Handle picture uploads
        $pictures = [];
        if ($request->hasFile('pictures')) {
            // Ensure directory exists on public disk
            \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('announcement-pictures');
            foreach ($request->file('pictures') as $picture) {
                if ($picture && $picture->isValid()) {
                    $filename = time() . '_' . uniqid() . '.' . $picture->getClientOriginalExtension();
                    // Ensure we write to the public disk reliably across environments
                    $picture->storeAs('announcement-pictures', $filename, 'public');
                    $relativePath = 'announcement-pictures/' . $filename;
                    // Optional: log if not found right after write (diagnostic)
                    if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($relativePath)) {
                        \Log::warning('Uploaded picture not found on public disk after store', [
                            'path' => $relativePath,
                        ]);
                    }
                    $pictures[] = $relativePath;
                }
            }
        }

        // Create sender announcement
        $announcement = SenderAnnouncement::create([
            'user_id' => $user->id,
            'pickup_city' => $request->pickup_city,
            'delivery_city' => $request->delivery_city,
            'preferred_date' => $request->preferred_date,
            'package_weight' => $request->package_weight,
            'description' => $request->description,
            'pictures' => $pictures,
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
     * Show edit sender announcement form.
     */
    public function editSenderAnnouncement($id): View
    {
        $user = Auth::user();
        $announcement = SenderAnnouncement::where('user_id', $user->id)
            ->with('details')
            ->findOrFail($id);

        return view('dashboard.create-sender-announcement', compact('announcement'));
    }

    /**
     * Update an existing sender announcement.
     */
    public function updateSenderAnnouncement(Request $request, $id)
    {
        $user = Auth::user();
        $announcement = SenderAnnouncement::where('user_id', $user->id)->findOrFail($id);

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
            'pictures.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        // Handle existing pictures deletion
        $existingPictures = $request->input('existing_pictures', []);
        $currentPictures = $announcement->pictures ?? [];
        
        // Find pictures to delete (pictures that were in currentPictures but not in existingPictures)
        $picturesToDelete = array_diff($currentPictures, $existingPictures);
        
        // Delete files from storage
        foreach ($picturesToDelete as $picturePath) {
            if (Storage::disk('public')->exists($picturePath)) {
                Storage::disk('public')->delete($picturePath);
            }
        }

        // Handle new picture uploads
        $newPictures = [];
        if ($request->hasFile('pictures')) {
            // Ensure directory exists on public disk
            \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('announcement-pictures');
            foreach ($request->file('pictures') as $picture) {
                if ($picture && $picture->isValid()) {
                    $filename = time() . '_' . uniqid() . '.' . $picture->getClientOriginalExtension();
                    $picture->storeAs('announcement-pictures', $filename, 'public');
                    $relativePath = 'announcement-pictures/' . $filename;
                    if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($relativePath)) {
                        \Log::warning('Uploaded picture not found on public disk after store (update)', [
                            'path' => $relativePath,
                        ]);
                    }
                    $newPictures[] = $relativePath;
                }
            }
        }

        // Combine existing (kept) pictures with new pictures
        $finalPictures = array_merge($existingPictures, $newPictures);

        // Update announcement
        $announcement->update([
            'pickup_city' => $request->pickup_city,
            'delivery_city' => $request->delivery_city,
            'preferred_date' => $request->preferred_date,
            'package_weight' => $request->package_weight,
            'description' => $request->description,
            'pictures' => $finalPictures,
        ]);

        // Update announcement details
        $announcement->details->update([
            'item_type' => $request->item_type,
            'dimensions' => $request->dimensions,
            'declared_value' => $request->declared_value,
            'additional_info' => $request->additional_info,
        ]);

        return redirect()->route('dashboard.my-announcements')
            ->with('success', 'Annonce de livraison mise à jour avec succès !');
    }

    /**
     * Show user's own announcements.
     */
    public function myAnnouncements(Request $request): View
    {
        $user = Auth::user();
        
        // Get user's own announcements with filters
        $myTravelerAnnouncements = $user->travelerAnnouncements()
            ->with('details')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('departure_city', 'like', "%{$search}%")
                      ->orWhere('arrival_city', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                if ($request->status === 'active') {
                    $query->where('is_active', true);
                } else {
                    $query->where('is_active', false);
                }
            })
            ->when($request->filled('sort'), function ($query) use ($request) {
                switch ($request->sort) {
                    case 'created_at_asc':
                        $query->orderBy('created_at', 'asc');
                        break;
                    case 'departure_date_asc':
                        $query->orderBy('departure_date', 'asc');
                        break;
                    default:
                        $query->orderBy('created_at', 'desc');
                }
            }, function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->get();
            
        $mySenderAnnouncements = $user->senderAnnouncements()
            ->with('details')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('pickup_city', 'like', "%{$search}%")
                      ->orWhere('delivery_city', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                if ($request->status === 'active') {
                    $query->where('is_active', true);
                } else {
                    $query->where('is_active', false);
                }
            })
            ->when($request->filled('sort'), function ($query) use ($request) {
                switch ($request->sort) {
                    case 'created_at_asc':
                        $query->orderBy('created_at', 'asc');
                        break;
                    case 'departure_date_asc':
                        $query->orderBy('preferred_date', 'asc');
                        break;
                    default:
                        $query->orderBy('created_at', 'desc');
                }
            }, function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->get();

        // Filter by type if specified
        if ($request->filled('type')) {
            if ($request->type === 'traveler') {
                $mySenderAnnouncements = collect();
            } elseif ($request->type === 'sender') {
                $myTravelerAnnouncements = collect();
            }
        }

        return view('dashboard.my-announcements', compact(
            'myTravelerAnnouncements',
            'mySenderAnnouncements'
        ));
    }

    /**
     * Show all announcements from other users.
     */
    public function allAnnouncements(Request $request): View
    {
        $user = Auth::user();
        
        // Get announcements with filters (include own as well)
        $otherTravelerAnnouncements = TravelerAnnouncement::query()
            ->where('is_active', true)
            ->where('status', 'active')
            ->with(['user', 'details'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('departure_city', 'like', "%{$search}%")
                      ->orWhere('arrival_city', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($userQuery) use ($search) {
                          $userQuery->where('name', 'like', "%{$search}%");
                      });
                });
            })
            ->when($request->filled('date_from'), function ($query) use ($request) {
                $query->where('departure_date', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->where('departure_date', '<=', $request->date_to);
            })
            ->when($request->filled('weight_min'), function ($query) use ($request) {
                $query->where('available_kg', '>=', $request->weight_min);
            })
            ->when($request->filled('weight_max'), function ($query) use ($request) {
                $query->where('available_kg', '<=', $request->weight_max);
            })
            ->when($request->filled('price_min'), function ($query) use ($request) {
                $query->whereHas('details', function ($detailQuery) use ($request) {
                    $detailQuery->where('price_per_kg', '>=', $request->price_min);
                });
            })
            ->when($request->filled('price_max'), function ($query) use ($request) {
                $query->whereHas('details', function ($detailQuery) use ($request) {
                    $detailQuery->where('price_per_kg', '<=', $request->price_max);
                });
            })
            ->when($request->filled('sort'), function ($query) use ($request) {
                switch ($request->sort) {
                    case 'created_at_asc':
                        $query->orderBy('created_at', 'asc');
                        break;
                    case 'departure_date_asc':
                        $query->orderBy('departure_date', 'asc');
                        break;
                    case 'price_asc':
                        $query->join('traveler_announcement_details', 'traveler_announcements.id', '=', 'traveler_announcement_details.announcement_id')
                              ->orderBy('traveler_announcement_details.price_per_kg', 'asc');
                        break;
                    case 'price_desc':
                        $query->join('traveler_announcement_details', 'traveler_announcements.id', '=', 'traveler_announcement_details.announcement_id')
                              ->orderBy('traveler_announcement_details.price_per_kg', 'desc');
                        break;
                    default:
                        $query->orderBy('created_at', 'desc');
                }
            }, function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->get();
            
        $otherSenderAnnouncements = SenderAnnouncement::query()
            ->where('is_active', true)
            ->where('status', 'active')
            ->with(['user', 'details'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('pickup_city', 'like', "%{$search}%")
                      ->orWhere('delivery_city', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($userQuery) use ($search) {
                          $userQuery->where('name', 'like', "%{$search}%");
                      });
                });
            })
            ->when($request->filled('date_from'), function ($query) use ($request) {
                $query->where('preferred_date', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->where('preferred_date', '<=', $request->date_to);
            })
            ->when($request->filled('weight_min'), function ($query) use ($request) {
                $query->where('package_weight', '>=', $request->weight_min);
            })
            ->when($request->filled('weight_max'), function ($query) use ($request) {
                $query->where('package_weight', '<=', $request->weight_max);
            })
            ->when($request->filled('sort'), function ($query) use ($request) {
                switch ($request->sort) {
                    case 'created_at_asc':
                        $query->orderBy('created_at', 'asc');
                        break;
                    case 'departure_date_asc':
                        $query->orderBy('preferred_date', 'asc');
                        break;
                    default:
                        $query->orderBy('created_at', 'desc');
                }
            }, function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->get();

        // Filter by type if specified
        if ($request->filled('type')) {
            if ($request->type === 'traveler') {
                $otherSenderAnnouncements = collect();
            } elseif ($request->type === 'sender') {
                $otherTravelerAnnouncements = collect();
            }
        }

        // Apply limit if specified
        $limit = $request->get('limit', 12);
        if ($limit) {
            $otherTravelerAnnouncements = $otherTravelerAnnouncements->take($limit);
            $otherSenderAnnouncements = $otherSenderAnnouncements->take($limit);
        }

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

    /**
     * Display the requests page with tabs for sent and received requests.
     */
    public function requests(Request $request): View
    {
        $user = Auth::user();
        $activeTab = $request->get('tab', 'sent');
        
        // Get sent requests (requests initiated by the user)
        $sentRequests = UserRequest::where('initiator_user_id', $user->id)
            ->with(['travelerAnnouncement.user', 'senderAnnouncement.user'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($userRequest) {
                if ($userRequest->traveler_announcement_id) {
                    // Travel request
                    $announcement = $userRequest->travelerAnnouncement;
                    $userRequest->title = "Demande de voyage {$announcement->departure_city} → {$announcement->arrival_city}";
                    $userRequest->departure_city = $announcement->departure_city;
                    $userRequest->arrival_city = $announcement->arrival_city;
                    $userRequest->date = $announcement->departure_date->format('d F Y');
                    $userRequest->weight = $announcement->available_kg;
                    $userRequest->price = $userRequest->proposed_price ?: ($announcement->details->price_per_kg ?? 0);
                    $userRequest->description = $announcement->description ?: 'Aucune description fournie.';
                    $userRequest->announcement_user = $announcement->user->name;
                    $userRequest->type = 'travel';
                } else {
                    // Delivery request (for future use)
                    $announcement = $userRequest->senderAnnouncement;
                    $userRequest->title = "Demande de livraison {$announcement->pickup_city} → {$announcement->delivery_city}";
                    $userRequest->departure_city = $announcement->pickup_city;
                    $userRequest->arrival_city = $announcement->delivery_city;
                    $userRequest->date = $announcement->preferred_date->format('d F Y');
                    $userRequest->weight = $announcement->package_weight;
                    $userRequest->price = $userRequest->proposed_price ?: 0;
                    $userRequest->description = $announcement->description ?: 'Aucune description fournie.';
                    $userRequest->announcement_user = $announcement->user->name;
                    $userRequest->type = 'delivery';
                }
                return $userRequest;
            });

        // Get received requests (requests where the user is the announcement owner)
        $receivedRequests = UserRequest::where('announcement_owner_id', $user->id)
            ->with(['travelerAnnouncement.user', 'senderAnnouncement.user', 'initiator'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($userRequest) {
                if ($userRequest->traveler_announcement_id) {
                    // Travel request
                    $announcement = $userRequest->travelerAnnouncement;
                    $userRequest->title = "Demande de voyage {$announcement->departure_city} → {$announcement->arrival_city}";
                    $userRequest->departure_city = $announcement->departure_city;
                    $userRequest->arrival_city = $announcement->arrival_city;
                    $userRequest->date = $announcement->departure_date->format('d F Y');
                    $userRequest->weight = $announcement->available_kg;
                    $userRequest->price = $userRequest->proposed_price ?: ($announcement->details->price_per_kg ?? 0);
                    $userRequest->description = $announcement->description ?: 'Aucune description fournie.';
                    $userRequest->requester_user = $userRequest->initiator->name;
                    $userRequest->type = 'travel';
                    // Keep the original properties that the view needs
                    $userRequest->traveler_announcement_id = $userRequest->traveler_announcement_id;
                    $userRequest->package_details_completed = $userRequest->package_details_completed;
                } else {
                    // Delivery request (for future use)
                    $announcement = $userRequest->senderAnnouncement;
                    $userRequest->title = "Demande de livraison {$announcement->pickup_city} → {$announcement->delivery_city}";
                    $userRequest->departure_city = $announcement->pickup_city;
                    $userRequest->arrival_city = $announcement->delivery_city;
                    $userRequest->date = $announcement->preferred_date->format('d F Y');
                    $userRequest->weight = $announcement->package_weight;
                    $userRequest->price = $userRequest->proposed_price ?: 0;
                    $userRequest->description = $announcement->description ?: 'Aucune description fournie.';
                    $userRequest->requester_user = $userRequest->initiator->name;
                    $userRequest->type = 'delivery';
                }
                return $userRequest;
            });

        // Apply filters based on active tab
        $requests = $activeTab === 'sent' ? $sentRequests : $receivedRequests;
        
        if ($request->filled('search')) {
            $search = $request->search;
            $requests = $requests->filter(function ($request) use ($search) {
                return str_contains(strtolower($request->title), strtolower($search)) ||
                       str_contains(strtolower($request->description), strtolower($search));
            });
        }

        if ($request->filled('type')) {
            $requests = $requests->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $requests = $requests->where('status', $request->status);
        }

        return view('requests.index', compact('requests', 'sentRequests', 'receivedRequests', 'activeTab'));
    }

    /**
     * Accept a received request.
     */
    public function acceptRequest(Request $request)
    {
        $user = Auth::user();
        $userRequest = UserRequest::where('id', $request->request_id)
            ->where('announcement_owner_id', $user->id)
            ->firstOrFail();

        if ($userRequest->status !== 'pending') {
            return back()->with('error', 'Cette demande ne peut plus être acceptée.');
        }

        // Update status to accepted
        $userRequest->update(['status' => 'accepted']);

        // Generate verification codes
        $userRequest->generateVerificationCodes();

        // Get the codes
        $codes = $userRequest->getVerificationCodes();

        // Send notification to the requester (initiator)
        $requester = $userRequest->initiator;
        \App\Models\Notification::createRequestAcceptedNotification($userRequest, $requester, $codes);

        // Send notification to the announcement owner (accepter)
        \App\Models\Notification::createRequestAcceptedNotification($userRequest, $user, $codes);

        return back()->with('success', 'Demande acceptée avec succès ! Les codes de vérification ont été générés et envoyés aux deux utilisateurs.');
    }

    /**
     * Reject a received request.
     */
    public function rejectRequest(Request $request)
    {
        $user = Auth::user();
        $userRequest = UserRequest::where('id', $request->request_id)
            ->where('announcement_owner_id', $user->id)
            ->firstOrFail();

        if ($userRequest->status !== 'pending') {
            return back()->with('error', 'Cette demande ne peut plus être refusée.');
        }

        $userRequest->update(['status' => 'rejected']);

        return back()->with('success', 'Demande refusée.');
    }

    /**
     * Create a new request for a traveler announcement.
     */
    public function createRequest(Request $request)
    {
        $request->validate([
            'traveler_announcement_id' => 'required|exists:traveler_announcements,id',
            'package_weight' => 'required|numeric|min:0.1',
            'pickup_location' => 'nullable|string|max:255',
            'delivery_location' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $travelerAnnouncement = TravelerAnnouncement::findOrFail($request->traveler_announcement_id);

        // Debug: Log the values being used
        \Log::info('Creating request with values:', [
            'user_id' => $user->id,
            'traveler_announcement_id' => $request->traveler_announcement_id,
            'announcement_owner_id' => $travelerAnnouncement->user_id,
            'traveler_announcement_user_id' => $travelerAnnouncement->user_id,
        ]);

        // Check if user is not requesting their own announcement
        if ($user->id === $travelerAnnouncement->user_id) {
            return back()->with('error', 'Vous ne pouvez pas demander votre propre annonce.');
        }

        // Ensure we have a valid announcement owner
        if (empty($travelerAnnouncement->user_id)) {
            return back()->with('error', 'Impossible de déterminer le propriétaire de l\'annonce.');
        }

        // Check if request already exists
        $existingRequest = UserRequest::where('initiator_user_id', $user->id)
            ->where('traveler_announcement_id', $request->traveler_announcement_id)
            ->first();

        if ($existingRequest) {
            // Allow new request if the previous one is completed or rejected
            if (!in_array($existingRequest->status, ['completed', 'rejected'])) {
                return back()->with('error', 'Vous avez déjà fait une demande pour cette annonce.');
            }
        }

        // Create the request
        $userRequest = UserRequest::create([
            'initiator_user_id' => $user->id,
            'initiator_role' => 'sender',
            'traveler_announcement_id' => $request->traveler_announcement_id,
            'announcement_owner_id' => $travelerAnnouncement->user_id,
            'pickup_location' => $request->pickup_location ?: $travelerAnnouncement->departure_city,
            'delivery_location' => $request->delivery_location ?: $travelerAnnouncement->arrival_city,
            'status' => 'pending',
        ]);

        // Debug: Log the created request
        \Log::info('Created UserRequest:', [
            'id' => $userRequest->id,
            'announcement_owner_id' => $userRequest->announcement_owner_id,
        ]);

        // Create a chat for this request
        if (empty($user->id) || empty($travelerAnnouncement->user_id)) {
            \Log::error('Cannot create chat: missing user IDs', [
                'user_id' => $user->id,
                'announcement_owner_id' => $travelerAnnouncement->user_id,
            ]);
            return back()->with('error', 'Erreur lors de la création du chat.');
        }

        $chat = Chat::create([
            'request_id' => $userRequest->id,
            'user1_id' => $user->id,
            'user2_id' => $travelerAnnouncement->user_id,
        ]);

        // Debug: Log the created chat
        \Log::info('Created Chat:', [
            'id' => $chat->id,
            'user1_id' => $chat->user1_id,
            'user2_id' => $chat->user2_id,
        ]);

        // Send a friendly, user-like message to the announcement owner with the request details
        $messageContent = "Bonjour,\n\n";
        $messageContent .= "Je souhaite vous confier un colis sur ce trajet." . "\n\n";
        $messageContent .= "Poids du colis : " . number_format($request->package_weight, 1) . " kg" . "\n";
        $messageContent .= "Récupération : " . $travelerAnnouncement->departure_city . " — " . $request->pickup_location . "\n";
        $messageContent .= "Livraison : " . $travelerAnnouncement->arrival_city . " — " . $request->delivery_location . "\n\n";
        if ($request->filled('description')) {
            $messageContent .= "Description : " . $request->description . "\n\n";
        }
        $messageContent .= "Dites-moi si cela vous convient ou si vous souhaitez ajuster les détails. Merci !";

        Message::create([
            'chat_id' => $chat->id,
            'sender_id' => $user->id,
            'message' => $messageContent,
            'sent_at' => now(),
        ]);

        return redirect()->route('messages.show', $chat->id)->with('success', 'Votre demande a été envoyée avec succès !');
    }

    /**
     * Create a new request for a sender announcement.
     */
    public function createSenderRequest(Request $request)
    {
        $request->validate([
            'sender_announcement_id' => 'required|exists:sender_announcements,id',
            'proposed_price' => 'nullable|numeric|min:0',
            'pickup_location' => 'nullable|string|max:255',
            'delivery_location' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $senderAnnouncement = SenderAnnouncement::findOrFail($request->sender_announcement_id);

        // Check if user is not requesting their own announcement
        if ($user->id === $senderAnnouncement->user_id) {
            return back()->with('error', 'Vous ne pouvez pas demander votre propre annonce.');
        }

        // Check if request already exists
        $existingRequest = UserRequest::where('initiator_user_id', $user->id)
            ->where('sender_announcement_id', $request->sender_announcement_id)
            ->first();

        if ($existingRequest) {
            // Allow new request if the previous one is completed or rejected
            if (!in_array($existingRequest->status, ['completed', 'rejected'])) {
                return back()->with('error', 'Vous avez déjà fait une demande pour cette annonce.');
            }
        }

        // Create the request
        $userRequest = UserRequest::create([
            'initiator_user_id' => $user->id,
            'initiator_role' => 'traveler',
            'sender_announcement_id' => $request->sender_announcement_id,
            'announcement_owner_id' => $senderAnnouncement->user_id,
            'proposed_price' => $request->proposed_price,
            'pickup_location' => $request->pickup_location ?: $senderAnnouncement->pickup_city,
            'delivery_location' => $request->delivery_location ?: $senderAnnouncement->delivery_city,
            'status' => 'pending',
        ]);

        // Create a chat for this request
        $chat = Chat::create([
            'request_id' => $userRequest->id,
            'user1_id' => $user->id,
            'user2_id' => $senderAnnouncement->user_id,
        ]);

        // Send a friendly, user-like message to the announcement owner with the request details
        $messageContent = "Bonjour,\n\n";
        $messageContent .= "Je suis intéressé(e) par votre annonce de livraison." . "\n\n";
        $messageContent .= "Prix proposé : " . number_format($request->proposed_price, 2) . " DH" . "\n";
        $messageContent .= "Récupération : " . $senderAnnouncement->pickup_city . " — " . $request->pickup_location . "\n";
        $messageContent .= "Livraison : " . $senderAnnouncement->delivery_city . " — " . $request->delivery_location . "\n\n";
        $messageContent .= "Dites-moi si cela vous convient ou si vous souhaitez ajuster les détails. Merci !";

        Message::create([
            'chat_id' => $chat->id,
            'sender_id' => $user->id,
            'message' => $messageContent,
            'sent_at' => now(),
        ]);

        return redirect()->route('messages.show', $chat->id)->with('success', 'Votre demande a été envoyée avec succès !');
    }

    /**
     * Delete a request.
     */
    public function deleteRequest(Request $request)
    {
        $user = Auth::user();
        $userRequest = UserRequest::where('initiator_user_id', $user->id)->findOrFail($request->request_id);

        $userRequest->delete();

        return back()->with('success', 'Demande supprimée avec succès !');
    }

    /**
     * Request detailed package form from the requester.
     */
    public function requestPackageForm(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:user_requests,id',
        ]);

        $user = Auth::user();
        $userRequest = UserRequest::findOrFail($request->request_id);

        // Check if user is the announcement owner
        if ($user->id !== $userRequest->announcement_owner_id) {
            return back()->with('error', 'Vous n\'êtes pas autorisé à effectuer cette action.');
        }

        // Check if this is a traveler announcement request
        if (!$userRequest->traveler_announcement_id) {
            return back()->with('error', 'Cette action n\'est disponible que pour les demandes de voyage.');
        }

        // Send a message requesting the detailed form
        $chat = $userRequest->chat;
        if ($chat) {
            $messageContent = "Bonjour,\n\n";
            $messageContent .= "Votre demande m'intéresse ! Pour pouvoir l'étudier en détail, j'ai besoin que vous remplissiez le formulaire complet avec :\n\n";
            $messageContent .= "• Photos du colis\n";
            $messageContent .= "• Dimensions exactes\n";
            $messageContent .= "• Type d'objet précis\n";
            $messageContent .= "• Description détaillée\n\n";
            $messageContent .= "Veuillez cliquer sur le bouton 'Remplir le formulaire détaillé' ci-dessous.\n\n";
            $messageContent .= "Merci !";

            Message::create([
                'chat_id' => $chat->id,
                'sender_id' => $user->id,
                'message' => $messageContent,
                'sent_at' => now(),
            ]);
        }

        // Mark that the form has been requested
        $userRequest->update(['form_requested' => true]);

        return back()->with('success', 'Demande de formulaire détaillé envoyée !');
    }

    /**
     * Request detailed traveler form for sender announcements.
     */
    public function requestTravelerForm(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:user_requests,id',
        ]);

        $user = Auth::user();
        $userRequest = UserRequest::findOrFail($request->request_id);

        // Check if user is the announcement owner
        if ($user->id !== $userRequest->announcement_owner_id) {
            return back()->with('error', 'Vous n\'êtes pas autorisé à effectuer cette action.');
        }

        // Send message requesting the form
        $chat = $userRequest->chat;
        if ($chat) {
            $messageContent = "Votre demande m'intéresse ! Pour pouvoir l'étudier en détail, j'ai besoin que vous remplissiez le formulaire complet de voyage avec :\n\n";
            $messageContent .= "• Villes de départ et d'arrivée\n";
            $messageContent .= "• Dates et heures de départ/arrivée\n";
            $messageContent .= "• Type de transport\n";
            $messageContent .= "• Prix par kg\n";
            $messageContent .= "• Description détaillée\n\n";
            $messageContent .= "Veuillez cliquer sur le bouton 'Remplir le formulaire détaillé' ci-dessous.\n\n";
            $messageContent .= "Merci !";

            Message::create([
                'chat_id' => $chat->id,
                'sender_id' => $user->id,
                'message' => $messageContent,
                'sent_at' => now(),
            ]);
        }

        // Mark that the form has been requested
        $userRequest->update(['form_requested' => true]);

        return back()->with('success', 'Demande de formulaire détaillé envoyée !');
    }

    /**
     * Submit detailed package form.
     */
    public function submitPackageForm(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:user_requests,id',
            'description' => 'required|string|max:1000',
            'weight' => 'required|numeric|min:0.1|max:100',
            'item_type' => 'required|string|max:255',
            'dimensions' => 'nullable|string|max:255',
            'breakable' => 'required|string|in:non_fragile,fragile_legere,fragile_elevee,extremement_fragile',
            'pictures.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        $user = Auth::user();
        $userRequest = UserRequest::findOrFail($request->request_id);

        // Check if user is the requester
        if ($user->id !== $userRequest->initiator_user_id) {
            return back()->with('error', 'Vous n\'êtes pas autorisé à effectuer cette action.');
        }

        // Handle picture uploads
        $pictures = [];
        if ($request->hasFile('pictures')) {
            \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('package-pictures');
            foreach ($request->file('pictures') as $picture) {
                if ($picture && $picture->isValid()) {
                    $filename = time() . '_' . uniqid() . '.' . $picture->getClientOriginalExtension();
                    $picture->storeAs('package-pictures', $filename, 'public');
                    $pictures[] = 'package-pictures/' . $filename;
                }
            }
        }

        // Create or update package
        $package = Package::updateOrCreate(
            ['request_id' => $userRequest->id],
            [
                'description' => $request->description,
                'weight' => $request->weight,
                'item_type' => $request->item_type,
                'dimensions' => $request->dimensions,
                'breakable' => $request->breakable,
                'image_url' => !empty($pictures) ? json_encode($pictures) : null,
            ]
        );

        // Mark package details as completed
        $userRequest->update(['package_details_completed' => true]);

        // Send confirmation message
        $chat = $userRequest->chat;
        if ($chat) {
            $messageContent = "Parfait ! J'ai complété le formulaire détaillé avec toutes les informations demandées.\n\n";
            $messageContent .= "Récapitulatif :\n";
            $messageContent .= "• Poids : " . number_format($request->weight, 1) . " kg\n";
            $messageContent .= "• Type : " . $request->item_type . "\n";
            if ($request->dimensions) {
                $messageContent .= "• Dimensions : " . $request->dimensions . "\n";
            }
            $messageContent .= "• Fragilité : " . $this->getBreakableLabel($request->breakable) . "\n";
            $messageContent .= "• Description : " . $request->description . "\n";
            if (!empty($pictures)) {
                $messageContent .= "• Photos : " . count($pictures) . " image(s) ajoutée(s)\n";
            }
            $messageContent .= "\nVous pouvez maintenant examiner ma demande en détail et décider de l'accepter ou non.";

            Message::create([
                'chat_id' => $chat->id,
                'sender_id' => $user->id,
                'message' => $messageContent,
                'sent_at' => now(),
            ]);
        }

        return back()->with('success', 'Formulaire détaillé soumis avec succès !');
    }

    /**
     * Submit detailed traveler form for sender announcements.
     */
    public function submitTravelerForm(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:user_requests,id',
            'departure_city' => 'required|string|max:255',
            'arrival_city' => 'required|string|max:255',
            'departure_date' => 'required|date|after_or_equal:today',
            'departure_time' => 'required',
            'estimated_arrival_date' => 'required|date|after_or_equal:departure_date',
            'estimated_arrival_time' => 'required',
            'transport_type' => 'required|string|max:255',
            'price_per_kg' => 'required|numeric|min:0',
            'description' => 'required|string|max:1000',
        ]);

        $user = Auth::user();
        $userRequest = UserRequest::findOrFail($request->request_id);

        // Check if user is the requester
        if ($user->id !== $userRequest->initiator_user_id) {
            return back()->with('error', 'Vous n\'êtes pas autorisé à effectuer cette action.');
        }

        // Create or update traveler announcement details
        $travelerDetails = TravelerAnnouncementDetail::updateOrCreate(
            ['request_id' => $userRequest->id],
            [
                'departure_city' => $request->departure_city,
                'arrival_city' => $request->arrival_city,
                'departure_date' => $request->departure_date,
                'departure_time' => $request->departure_time,
                'estimated_arrival_date' => $request->estimated_arrival_date,
                'estimated_arrival_time' => $request->estimated_arrival_time,
                'transport_type' => $request->transport_type,
                'price_per_kg' => $request->price_per_kg,
                'description' => $request->description,
            ]
        );

        // Mark package details as completed (reusing the same field for both types)
        $userRequest->update(['package_details_completed' => true]);

        // Send confirmation message
        $chat = $userRequest->chat;
        if ($chat) {
            $messageContent = "Parfait ! J'ai complété le formulaire détaillé de voyage avec toutes les informations demandées.\n\n";
            $messageContent .= "Récapitulatif :\n";
            $messageContent .= "• Départ : {$request->departure_city} le {$request->departure_date} à {$request->departure_time}\n";
            $messageContent .= "• Arrivée : {$request->arrival_city} le {$request->estimated_arrival_date} à {$request->estimated_arrival_time}\n";
            $messageContent .= "• Transport : {$request->transport_type}\n";
            $messageContent .= "• Prix : {$request->price_per_kg} DH/kg\n";
            $messageContent .= "• Description : {$request->description}\n\n";
            $messageContent .= "Vous pouvez maintenant examiner ma demande en détail et décider de l'accepter ou non.";

            Message::create([
                'chat_id' => $chat->id,
                'sender_id' => $user->id,
                'message' => $messageContent,
                'sent_at' => now(),
            ]);
        }

        return back()->with('success', 'Formulaire détaillé soumis avec succès !');
    }

    private function getBreakableLabel($breakable)
    {
        switch ($breakable) {
            case 'non_fragile':
                return 'Non fragile';
            case 'fragile_legere':
                return 'Fragile légère';
            case 'fragile_elevee':
                return 'Fragile élevée';
            case 'extremement_fragile':
                return 'Extrêmement fragile';
            default:
                return 'Inconnu';
        }
    }
}
