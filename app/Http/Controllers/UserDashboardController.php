<?php

namespace App\Http\Controllers;

use App\Models\SenderAnnouncement;
use App\Models\SenderAnnouncementDetail;
use App\Models\TravelerAnnouncement;
use App\Models\TravelerAnnouncementDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
     * Show user's requests.
     */
    public function requests(Request $request): View
    {
        // For now, we'll create sample data since we don't have a requests table
        $requests = collect([
            (object) [
                'id' => 1,
                'type' => 'travel',
                'title' => 'Demande de voyage Paris → Lyon',
                'departure_city' => 'Paris',
                'arrival_city' => 'Lyon',
                'date' => '15 Janvier 2024',
                'weight' => 3.5,
                'price' => 25,
                'status' => 'pending',
                'description' => 'Je recherche un transport pour un petit colis de 3.5kg de Paris à Lyon. Le colis contient des documents importants et doit arriver avant le 16 janvier.',
                'created_at' => '10 Janvier 2024',
                'responses_count' => 2
            ],
            (object) [
                'id' => 2,
                'type' => 'delivery',
                'title' => 'Demande de livraison Marseille → Nice',
                'departure_city' => 'Marseille',
                'arrival_city' => 'Nice',
                'date' => '20 Janvier 2024',
                'weight' => 8,
                'price' => 40,
                'status' => 'accepted',
                'description' => 'Transport d\'un colis fragile de 8kg de Marseille à Nice. Le colis contient des équipements électroniques et nécessite un transport soigneux.',
                'created_at' => '12 Janvier 2024',
                'accepted_by' => 'Jean Dupont'
            ],
            (object) [
                'id' => 3,
                'type' => 'travel',
                'title' => 'Demande de voyage Bordeaux → Toulouse',
                'departure_city' => 'Bordeaux',
                'arrival_city' => 'Toulouse',
                'date' => '5 Janvier 2024',
                'weight' => 2,
                'price' => 15,
                'status' => 'completed',
                'description' => 'Transport d\'un petit colis de 2kg de Bordeaux à Toulouse. Le transport s\'est bien déroulé et le colis est arrivé à destination.',
                'created_at' => '2 Janvier 2024',
                'completed_at' => '5 Janvier 2024'
            ]
        ]);

        // Apply filters
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

        return view('requests.index', compact('requests'));
    }
}
