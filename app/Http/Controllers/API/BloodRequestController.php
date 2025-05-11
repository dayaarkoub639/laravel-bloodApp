<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Personne;
use App\Models\Demande;
use App\Models\Groupage;
use App\Events\BloodRequestEvent;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\FirebaseService;

class BloodRequestController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function findNearbyDonors(Request $request)
    {
        Log::info('Blood donation request received', [
            'groupage' => $request->groupage,
            'position' => $request->currentUserPosition,
            'demandeur_id' => $request->idDemandeur
        ]);

        try {
            $request->validate([
                'groupage' => 'required|string',
                'currentUserPosition' => 'required|string',
                'idDemandeur' => 'required|int',  
            ]);

            Log::info('Request validation passed');

            [$latitude, $longitude] = explode(',', $request->currentUserPosition);
            $latitude = (float) $latitude;
            $longitude = (float) $longitude;

            if (!is_numeric($latitude) || !is_numeric($longitude) ||
                $latitude < -90 || $latitude > 90 ||
                $longitude < -180 || $longitude > 180) {
                Log::warning('Invalid coordinates provided', compact('latitude', 'longitude'));
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid coordinates'
                ], 400);
            }

            Log::debug('Valid coordinates extracted', compact('latitude', 'longitude'));

            $bloodGroup = $request->groupage;
            Log::info('Using blood group directly', ['blood_group' => $bloodGroup]);

            $dateLimite = Carbon::now()->subMonths(3);
            Log::debug('Date limit for donors', ['date_limite' => $dateLimite->toDateTimeString()]);

            // Refined Query: Assumes idGroupage is a VARCHAR
            $baseDonneursQuery = Personne::where('idGroupage', $bloodGroup)
                ->where('idUser', '!=', $request->idDemandeur)
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->where(function ($query) {
                    $query->where('serologie', 0)
                          ->orWhereNull('serologie');
                })
                ->where(function ($query) use ($dateLimite) {
                    $query->where('dernierDateDon', '<=', $dateLimite)
                          ->orWhereNull('dernierDateDon');
                });

            $allDonneurs = $baseDonneursQuery->get();

            Log::info('Base donor query completed', [
                'donors_found_initially' => $allDonneurs->count(),
                'blood_group' => $bloodGroup
            ]);

            $donneurs = collect();

            if ($allDonneurs->isNotEmpty()) {
                $donneurs = $allDonneurs->map(function ($donneur) use ($latitude, $longitude) {
                    $donLat = (float) $donneur->latitude;
                    $donLng = (float) $donneur->longitude;

                    if (!is_numeric($donLat) || !is_numeric($donLng)) {
                        $donneur->distance = 999999;
                        return $donneur;
                    }

                    try {
                        $donneur->distance = $this->calculerDistance(
                            $latitude,
                            $longitude,
                            $donLat,
                            $donLng
                        );
                    } catch (\Exception $e) {
                        Log::warning('Error calculating distance for donor ' . $donneur->idUser . ': ' . $e->getMessage());
                        $donneur->distance = 999999;
                    }
                    return $donneur;
                })
                ->filter(fn($donneur) => isset($donneur->distance) && $donneur->distance < 20)
                ->sortBy('distance')
                ->values();

                $donneurs = $donneurs->take(3);

                Log::info('Distance-filtered donors', [
                    'filtered_count' => $donneurs->count(),
                    'original_count_with_coords' => $allDonneurs->count()
                ]);
            }

            if ($donneurs->isEmpty()) {
                Log::warning('No donors found matching criteria and within radius', [
                    'blood_group' => $bloodGroup,
                    'radius_km' => 20,
                    'position' => "$latitude,$longitude"
                ]);
                return response()->json([
                    'message' => 'Aucun donneur trouvé à proximité',
                    'success' => false
                ], 404);
            }

            $demandeIdForNotification = 0;
            try {
                $demande = Demande::create([
                    'dateDemande' => Carbon::now(),
                    'groupageDemande' => $bloodGroup,
                    'idDemandeur' => $request->idDemandeur,
                    'typeDemande' => "urgent",
                    'nbreDonneursEnvoyes' => $donneurs->count(),
                    'lieuDemande' => $request->input('lieuDemande', ''),
                    'serviceMedical' => $request->input('serviceMedical', ''),
                    'quantiteDemande' => $request->input('quantiteDemande', 1),
                    'typeMaladie' => $request->input('typeMaladie', ''),
                    'numeroDossierMedical' => $request->input('numeroDossierMedical', ''),
                    'notes' => $request->input('notes', ''),
                    'latitude' => $latitude,  // Store requester's coordinates
                    'longitude' => $longitude,
                ]);
                $demandeIdForNotification = $demande->id;
                Log::info('Created demand record', ['demand_id' => $demandeIdForNotification]);

            } catch (\Exception $e) {
                Log::error('Failed to create demand record', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                // Consider: Should the whole operation fail if demand creation fails?
            }


            $processedDonneurs = [];
            foreach ($donneurs as $donneur) {
                Log::debug('Processing donor for notification', [
                    'donor_id' => $donneur->idUser,
                    'has_fcm_token' => !empty($donneur->fcm_token)
                ]);

                $cleanDonneur = [
                    'idUser' => $donneur->idUser,
                    'distance' => round($donneur->distance, 2),
                    'latitude' => $donneur->latitude,
                    'longitude' => $donneur->longitude,
                    'groupage' => $donneur->idGroupage ?? $bloodGroup,
                    'pseudo' => "Donor",
                    'centreProche' => "Nearest Center",
                ];

                try {
                    if ($donneur->user && $donneur->user->pseudo) {
                         $cleanDonneur['pseudo'] = $donneur->user->pseudo;
                    } elseif ($donneur->pseudo) {
                        $cleanDonneur['pseudo'] = $donneur->pseudo;
                    }
                } catch (\Exception $e) {
                    Log::warning('Error getting donor pseudo for ' . $donneur->idUser . ': ' . $e->getMessage());
                }

                try {
                    $cleanDonneur['centreProche'] = $this->getCentreProche($donneur->latitude, $donneur->longitude);
                } catch (\Exception $e) {
                    Log::warning('Error getting nearest center for donor ' . $donneur->idUser . ': ' . $e->getMessage());
                }

                $eventData = [
                    'groupage' => $bloodGroup,
                    'position' => "{$latitude},{$longitude}",
                    'message' => "Besoin urgent de sang du groupe {$bloodGroup} !",
                    'user_id' => $donneur->idUser,
                    'demandeurId' => $request->idDemandeur,
                    'centreProche' => $cleanDonneur['centreProche'],
                    'requestId' => $demandeIdForNotification,
                ];

                try {
                    broadcast(new BloodRequestEvent($eventData));
                    Log::info('BloodRequestEvent broadcast to donor_id: ' . $donneur->idUser);
                } catch (\Exception $e) {
                    Log::error('Failed to broadcast BloodRequestEvent for donor ' . $donneur->idUser . ': ' . $e->getMessage());
                }

                if (!empty($donneur->fcm_token)) {
                    Log::info('Attempting to send FCM notification', ['to_user_id' => $donneur->idUser]);
                    try {
                        $this->firebaseService->sendNotification(
                            $donneur->fcm_token,
                            [
                                'title' => 'Urgent Blood Request',
                                'body' => "Blood type {$bloodGroup} needed urgently. Nearest center: {$cleanDonneur['centreProche']}",
                            ],
                            [
                                'type' => 'blood_request',
                                'groupage' => $bloodGroup,
                                'demandeurId' => (string) $request->idDemandeur,
                                'centreProche' => $cleanDonneur['centreProche'],
                                'requestId' => (string) $demandeIdForNotification,
                                'requester_position' => "{$latitude},{$longitude}",
                            ]
                        );
                        Log::info('FCM notification sent successfully to donor_id: ' . $donneur->idUser);
                    } catch (\Exception $e) {
                        Log::error('Failed to send FCM notification to donor ' . $donneur->idUser . ': ' . $e->getMessage());
                    }
                } else {
                    Log::warning('Skipping FCM notification - no token for donor_id: ' . $donneur->idUser);
                }
                $processedDonneurs[] = $cleanDonneur;
            }

            Log::info('Blood request process completed successfully', [
                'demand_id' => $demandeIdForNotification,
                'donors_notified_count' => count($processedDonneurs)
            ]);

            return response()->json([
                'success' => true,
                'donneurs' => $processedDonneurs,
                'requestId' => $demandeIdForNotification
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Error in findNearbyDonors', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error in findNearbyDonors', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request.',
            ], 500);
        }
    }

    private function calculerDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return $distance;
    }

    private function getCentreProche($lat, $long)
    {
        if (!is_numeric($lat) || !is_numeric($long)) {
            return "Invalid coordinates for center search";
        }
        try {
            $centre = DB::table('centres')
                ->select('nom', 'id', DB::raw("
                    6371 * acos (
                      cos ( radians(?) )
                      * cos( radians( latitude ) )
                      * cos( radians( longitude ) - radians(?) )
                      + sin ( radians(?) )
                      * sin( radians( latitude ) )
                    ) AS distance"))
                ->setBindings([$lat, $long, $lat])
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->orderBy('distance')
                ->limit(1)
                ->first();

            if ($centre) {
                return $centre->nom . " (ID: " . $centre->id . ")";
            }
        } catch (\Exception $e) {
            Log::error('Error in getCentreProche: ' . $e->getMessage());
            return "Error finding center";
        }
        return "Nearest Center Not Found";
    }

    public function updateLocation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idUser' => 'required',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $personne = Personne::where('idUser', $request->idUser)->first();

            if (!$personne) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            $personne->update([
                'latitude' => $request->latitude,
                'longitude' => $request->longitude
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Location updated successfully',
                'data' => $personne
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating location for user ' . $request->idUser . ': ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error while updating location.',
            ], 500);
        }
    }

    public function accepterDemande(Request $request) {
        $validator = Validator::make($request->all(), [
            'idUser' => 'required|string', 
            'idDemande' => 'required|exists:demandes,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $personneDonor = Personne::where('idUser', $request->idUser)->first();
            if (!$personneDonor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Donor (Personne) not found'
                ], 404);
            }

            $demande = Demande::find($request->idDemande);

            $personneDonor->demandes()->syncWithoutDetaching([
                $request->idDemande => ['date_acceptation' => now()]
            ]);

            $centreProche = "Unknown Center";
            if (is_numeric($personneDonor->latitude) && is_numeric($personneDonor->longitude)) {
                $centreProche = $this->getCentreProche($personneDonor->latitude, $personneDonor->longitude);
            }

            $requesterPersonne = Personne::where('idUser', $demande->idDemandeur)->first();

            if ($requesterPersonne) {
                $notificationTitle = 'Blood Request Accepted!';
                $donorPseudo = $personneDonor->user->pseudo ?? 'N/A'; // Safe access
                $notificationBody = "Donor {$personneDonor->idUser} (Pseudo: {$donorPseudo}) has accepted your request for blood group {$demande->groupageDemande}.";
                $notificationData = [
                    'type' => 'request_accepted',
                    'requestId' => (string) $demande->id,
                    'donorId' => (string) $personneDonor->idUser,
                    'donorPseudo' => $donorPseudo,
                    'centreProcheDonor' => $centreProche,
                ];
                Log::info("Preparing to notify requester {$requesterPersonne->idUser} for demand {$demande->id}");

                if (!empty($requesterPersonne->fcm_token)) {
                    try {
                        $this->firebaseService->sendNotification(
                            $requesterPersonne->fcm_token,
                            ['title' => $notificationTitle, 'body' => $notificationBody],
                            $notificationData
                        );
                        Log::info("FCM acceptance notification sent to requester {$requesterPersonne->idUser}");
                    } catch (\Exception $e) {
                        Log::error("Failed to send FCM acceptance to requester {$requesterPersonne->idUser}: " . $e->getMessage());
                    }
                } else {
                     Log::warning("Requester {$requesterPersonne->idUser} has no FCM token for acceptance notification.");
                }

                try {
                    broadcast(new BloodRequestEvent([
                        'user_id' => $demande->idDemandeur,
                        'message' => $notificationBody,
                        'data' => $notificationData
                    ]));
                    Log::info("Acceptance event broadcast to requester_id: {$demande->idDemandeur}");
                } catch (\Exception $e) {
                    Log::error("Failed to broadcast acceptance event to requester {$demande->idDemandeur}: " . $e->getMessage());
                }

            } else {
                Log::warning("Could not find requester (Personne) with ID {$demande->idDemandeur} to send acceptance notification.");
            }

            return response()->json([
                'success' => true,
                'message' => 'Request accepted successfully. Requester has been notified.',
                'donor_center_info' => $centreProche,
                'demande_id' => $demande->id,
            ]);

        } catch (\Exception $e) {
            Log::error('Error accepting blood request: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Server error while accepting request.',
            ], 500);
        }
    }


    public function getRequestStatus(Request $request, $id) {
        try {
            $validator = Validator::make($request->all(), [
                'idDemandeur' => 'required|integer', 
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error: idDemandeur is required.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $demande = Demande::find($id);

            if (!$demande) {
                return response()->json([
                    'success' => false,
                    'message' => 'Request not found'
                ], 404);
            }

            if ($demande->idDemandeur != $request->input('idDemandeur')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to view this request status.'
                ], 403);
            }

            $acceptedDonorsDetails = $demande->personnes()
                ->withPivot('date_acceptation')
                ->get()
                ->map(function ($personne) {
                    return [
                        'idUser' => $personne->idUser,
                        'pseudo' => $personne->user->pseudo ?? 'N/A',
                        'numeroTlp1' => $personne->numeroTlp1,
                        'date_acceptation' => Carbon::parse($personne->pivot->date_acceptation)->toDateTimeString(),
                    ];
                });

            return response()->json([
                'success' => true,
                'requestDetails' => $demande,
                'acceptedDonors' => $acceptedDonorsDetails,
                'totalAccepted' => $acceptedDonorsDetails->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching request status for demand_id ' . $id . ': ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Server error while fetching request status.',
            ], 500);
        }
    }

    public function updatePhone(Request $request) {
        $validator = Validator::make($request->all(), [
            'idPersonne' => 'required|string|exists:personnes,idUser',  // Ensure integer and exists
            'numeroTlp1' => [
                'required',
                'string',
                Rule::unique('personnes', 'numeroTlp1')->ignore($request->idPersonne, 'idUser'),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $personne = Personne::where('idUser', $request->idPersonne)->firstOrFail();
            $personne->numeroTlp1 = $request->numeroTlp1;
            $personne->save();

            return response()->json([
                'success' => true,
                'message' => 'Numéro de téléphone mis à jour avec succès.',
                'data' => $personne
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             return response()->json([
                'success' => false,
                'message' => 'Personne not found.',
            ], 404);
        } catch (\Exception $e) {
             Log::error('Error updating phone for personne_id ' . $request->idPersonne . ': ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error while updating phone number.',
            ], 500);
        }
    }

    public function updateFcmToken(Request $request)
    {
        $request->validate([
            'idUser' => 'required|string|exists:personnes,idUser', 
            'fcm_token' => 'required|string',
        ]);

        try {
            $personne = Personne::where('idUser', $request->idUser)->firstOrFail();
            $personne->fcm_token = $request->fcm_token;
            $personne->save();

            return response()->json([
                'success' => true,
                'message' => 'FCM token updated successfully'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             return response()->json([
                'success' => false,
                'message' => 'Personne not found for FCM token update.',
            ], 404);
        }catch (\Exception $e) {
            Log::error('Error updating FCM token for user_id ' . $request->idUser . ': ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error while updating FCM token.',
            ], 500);
        }
    }
}