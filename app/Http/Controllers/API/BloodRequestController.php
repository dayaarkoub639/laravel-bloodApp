<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Personne;
use App\Models\Demande;
// use App\Models\Groupage; // Not used directly here
use App\Events\BloodRequestEvent;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule; // Not used directly here
// use Carbon\Carbon; // Not used directly here
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

    // findNearbyDonors method remains as is (it's working)
    public function findNearbyDonors(Request $request)
    {
        Log::info('Blood donation request received', [
            'groupage' => $request->groupage,
            'position' => $request->currentUserPosition,
            'demandeur_id' => $request->idDemandeur
        ]);
        Log::debug('findNearbyDonors: Full request object:', $request->toArray());


        try {
            $request->validate([
                'groupage' => 'required|string',
                'currentUserPosition' => 'required|string',
                'idDemandeur' => 'required|int',
            ]);

            Log::info('Request validation passed for findNearbyDonors');
            Log::debug('findNearbyDonors: Validated data:', $request->all());


            [$latitude, $longitude] = explode(',', $request->currentUserPosition);
            $latitude = (float) $latitude;
            $longitude = (float) $longitude;

            if (!is_numeric($latitude) || !is_numeric($longitude) ||
                $latitude < -90 || $latitude > 90 ||
                $longitude < -180 || $longitude > 180) {
                Log::warning('Invalid coordinates provided for findNearbyDonors', compact('latitude', 'longitude'));
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid coordinates'
                ], 400);
            }

            Log::debug('Valid coordinates extracted for findNearbyDonors', compact('latitude', 'longitude'));

            $bloodGroup = $request->groupage;
            Log::info('Using blood group directly for findNearbyDonors', ['blood_group' => $bloodGroup]);

            $dateLimite = \Carbon\Carbon::now()->subMonths(3); // Use fully qualified name for Carbon
            Log::debug('Date limit for donors in findNearbyDonors', ['date_limite' => $dateLimite->toDateTimeString()]);

            $baseDonneursQuery = Personne::where('idGroupage', $bloodGroup)
                ->where('idUser', '!=', $request->idDemandeur)
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->where(function ($query) {
                    $query->where('serologie', "Négatif")
                          ->orWhereNull('serologie');
                })
                ->where(function ($query) use ($dateLimite) {
                    $query->where('dernierDateDon', '<=', $dateLimite)
                          ->orWhereNull('dernierDateDon');
                });

            $allDonneurs = $baseDonneursQuery->get();

            Log::info('Base donor query completed for findNearbyDonors', [
                'donors_found_initially' => $allDonneurs->count(),
                'blood_group' => $bloodGroup
            ]);
            Log::debug('findNearbyDonors: All potential donors SQL query (approx):', [$baseDonneursQuery->toSql(), $baseDonneursQuery->getBindings()]);


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
                        Log::warning('Error calculating distance for donor ' . $donneur->idUser . ' in findNearbyDonors: ' . $e->getMessage());
                        $donneur->distance = 999999;
                    }
                    return $donneur;
                })
                ->filter(fn($donneur) => isset($donneur->distance) && $donneur->distance < 20)
                ->sortBy('distance')
                ->values();

                $donneurs = $donneurs->take(3);

                Log::info('Distance-filtered donors for findNearbyDonors', [
                    'filtered_count' => $donneurs->count(),
                    'original_count_with_coords' => $allDonneurs->count()
                ]);
                Log::debug('findNearbyDonors: Filtered donors list (top 3):', $donneurs->toArray());

            }

            if ($donneurs->isEmpty()) {
                Log::warning('No donors found matching criteria and within radius for findNearbyDonors', [
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
            $demandeToReturn = null;

            try {
                Log::debug('findNearbyDonors: Attempting to create Demande record.');
                $demande = Demande::create([
                    'dateDemande' => \Carbon\Carbon::now(),
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
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                ]);
                $demandeIdForNotification = $demande->id;
                $demandeToReturn = $demande;
                Log::info('Created demand record in findNearbyDonors', ['demand_id' => $demandeIdForNotification]);
                Log::debug('findNearbyDonors: Created Demande record details:', $demande->toArray());


            } catch (\Exception $e) {
                Log::error('Failed to create demand record in findNearbyDonors', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }

            $processedDonneurs = [];
            foreach ($donneurs as $donneur) {
                Log::debug('Processing donor for notification in findNearbyDonors', [
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
                     Log::warning('Error getting donor pseudo for ' . $donneur->idUser . ' in findNearbyDonors: ' . $e->getMessage());
                }
                try {
                    $cleanDonneur['centreProche'] = $this->getCentreProcheName($donneur->latitude, $donneur->longitude);
                } catch (\Exception $e) {
                    Log::warning('Error getting nearest center name for donor ' . $donneur->idUser . ' in findNearbyDonors: ' . $e->getMessage());
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
                Log::debug('findNearbyDonors: Broadcasting event data for donor:', ['donor_id' => $donneur->idUser, 'event_data' => $eventData]);

                try {
                    broadcast(new BloodRequestEvent($eventData));
                    Log::info('BloodRequestEvent broadcast to donor_id: ' . $donneur->idUser . ' in findNearbyDonors');
                } catch (\Exception $e) {
                    Log::error('Failed to broadcast BloodRequestEvent for donor ' . $donneur->idUser . ' in findNearbyDonors: ' . $e->getMessage());
                }

                if (!empty($donneur->fcm_token)) {
                    Log::info('Attempting to send FCM notification in findNearbyDonors', ['to_user_id' => $donneur->idUser]);
                    $fcmPayload = [
                        'title' => 'Urgent Blood Request',
                        'body' => "Blood type {$bloodGroup} needed. Requester is near {$cleanDonneur['centreProche']}.",
                    ];
                    $fcmDataPayload = [
                        'type' => 'blood_request',
                        'groupage' => $bloodGroup,
                        'demandeurId' => (string) $request->idDemandeur,
                        'centreProche' => $cleanDonneur['centreProche'],
                        'requestId' => (string) $demandeIdForNotification,
                        'requester_position' => "{$latitude},{$longitude}",
                    ];
                    Log::debug('findNearbyDonors: Sending FCM notification to donor:', ['donor_id' => $donneur->idUser, 'fcm_payload' => $fcmPayload, 'fcm_data' => $fcmDataPayload]);

                    try {
                        $this->firebaseService->sendNotification(
                            $donneur->fcm_token,
                            $fcmPayload,
                            $fcmDataPayload
                        );
                        Log::info('FCM notification sent successfully to donor_id: ' . $donneur->idUser . ' in findNearbyDonors');
                    } catch (\Exception $e) {
                        Log::error('Failed to send FCM notification to donor ' . $donneur->idUser . ' in findNearbyDonors: ' . $e->getMessage());
                    }
                } else {
                    Log::warning('Skipping FCM notification - no token for donor_id: ' . $donneur->idUser . ' in findNearbyDonors');
                }
                $processedDonneurs[] = $cleanDonneur;
            }

            Log::info('Blood request process completed successfully for findNearbyDonors', [
                'demand_id' => $demandeIdForNotification,
                'donors_notified_count' => count($processedDonneurs)
            ]);

            return response()->json([
                'success' => true,
                'donneurs' => $processedDonneurs,
                'requestId' => $demandeIdForNotification,
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

    private function getCentreProcheName($lat, $long)
    {
        if (!is_numeric($lat) || !is_numeric($long)) {
            Log::warning('getCentreProcheName called with invalid coordinates', ['lat' => $lat, 'long' => $long]);
            return "Invalid coordinates for center search";
        }
        try {
            Log::debug('getCentreProcheName: Searching for nearest center', ['lat' => $lat, 'long' => $long]);
            $centre = DB::table('centres')
                ->select('nom', 'id')
                ->selectRaw("6371 * acos ( cos ( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin ( radians(?) ) * sin( radians( latitude ) ) ) AS distance", [$lat, $long, $lat])
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->orderBy('distance')
                ->limit(1)
                ->first();

            if ($centre) {
                Log::debug('getCentreProcheName: Found nearest center', ['center_name' => $centre->nom, 'center_id' => $centre->id]);
                return $centre->nom . " (ID: " . $centre->id . ")";
            }
        } catch (\Exception $e) {
            Log::error('Error in getCentreProcheName: ' . $e->getMessage(), ['lat' => $lat, 'long' => $long]);
            return "Error finding center name";
        }
        Log::warning('getCentreProcheName: Nearest center not found', ['lat' => $lat, 'long' => $long]);
        return "Nearest Center Not Found";
    }

    private function getCentreProcheWithCoords($lat, $long)
    {
        if (!is_numeric($lat) || !is_numeric($long)) {
            Log::warning('getCentreProcheWithCoords called with invalid coordinates', ['lat' => $lat, 'long' => $long]);
            return null;
        }
        try {
            Log::debug('getCentreProcheWithCoords: Searching for nearest center with coords', ['lat' => $lat, 'long' => $long]);
            $centre = DB::table('centres')
                ->select('nom', 'id', 'latitude', 'longitude', DB::raw("
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
                $centerDetails = [
                    'name' => $centre->nom,
                    'id' => $centre->id,
                    'latitude' => (float) $centre->latitude,
                    'longitude' => (float) $centre->longitude,
                ];
                Log::debug('getCentreProcheWithCoords: Found nearest center with coords', $centerDetails);
                return $centerDetails;
            }
        } catch (\Exception $e) {
            Log::error('Error in getCentreProcheWithCoords: ' . $e->getMessage(), ['lat' => $lat, 'long' => $long]);
            return null;
        }
        Log::warning('getCentreProcheWithCoords: Nearest center not found', ['lat' => $lat, 'long' => $long]);
        return null;
    }

    public function updateLocation(Request $request)
    {
        Log::info('Attempting to update location', $request->all());
        Log::debug('updateLocation: Full request object:', $request->toArray());
        $validator = Validator::make($request->all(), [
            'idUser' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            Log::warning('Update location validation failed', ['errors' => $validator->errors(), 'request' => $request->all()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        Log::debug('updateLocation: Validation passed for user:', ['idUser' => $request->idUser]);


        try {
            $personne = Personne::where('idUser', $request->idUser)->first();

            if (!$personne) {
                Log::warning('User not found for location update', ['idUser' => $request->idUser]);
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }
            Log::debug('updateLocation: User found for location update', ['idUser' => $personne->idUser]);


            $personne->update([
                'latitude' => $request->latitude,
                'longitude' => $request->longitude
            ]);
            Log::info('Location updated successfully for user', ['idUser' => $request->idUser]);
            Log::debug('updateLocation: User after location update:', $personne->toArray());


            return response()->json([
                'success' => true,
                'message' => 'Location updated successfully',
                'data' => $personne
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating location for user ' . $request->idUser . ': ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Server error while updating location.',
            ], 500);
        }
    }

    public function accepterDemande(Request $request) {
        Log::info('AccepterDemande: Request received.', ['request_data' => $request->all()]);
        Log::debug('AccepterDemande: Full request object for debugging:', $request->toArray());

        $validator = Validator::make($request->all(), [
            'idUser' => 'required|string',
            'idDemande' => 'required|integer|exists:demandes,id'
        ]);

        if ($validator->fails()) {
            Log::warning('AccepterDemande: Validation failed.', [
                'errors' => $validator->errors(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        Log::info('AccepterDemande: Validation passed.');
        Log::debug('AccepterDemande: Validated data:', $request->all());

        try {
            $donorId = $request->idUser;
            $demandeId = $request->idDemande;

            Log::info('AccepterDemande: Processing acceptance.', ['donor_id' => $donorId, 'demande_id' => $demandeId]);
            Log::debug('AccepterDemande: Fetching donor and demande details.');

            $personneDonor = Personne::where('idUser', $donorId)->first();
            if (!$personneDonor) {
                Log::error('AccepterDemande: Donor (Personne) not found.', ['donor_id' => $donorId]);
                return response()->json([
                    'success' => false,
                    'message' => 'Donor (Personne) not found'
                ], 404);
            }
            Log::info('AccepterDemande: Donor found.', ['donor_id' => $personneDonor->idUser]);
            Log::debug('AccepterDemande: Donor (Personne) details:', $personneDonor->toArray());


            $demande = Demande::find($demandeId);
            if (!$demande) {
                Log::error('AccepterDemande: Demande not found.', ['demande_id' => $demandeId]);
                return response()->json([
                    'success' => false,
                    'message' => 'Blood request (Demande) not found'
                ], 404);
            }
            Log::info('AccepterDemande: Demande found.', ['demande_id' => $demande->id, 'idDemandeur' => $demande->idDemandeur, 'requester_lat' => $demande->latitude, 'requester_lng' => $demande->longitude]);
            Log::debug('AccepterDemande: Demande details:', $demande->toArray());


            $alreadyAccepted = DB::table('demande_personne')
                                ->where('demande_id', $demandeId)
                                ->where('personne_id', $personneDonor->id) // Assuming 'id' is the primary key of 'personnes' table
                                ->exists();

            Log::debug('AccepterDemande: Check if already accepted.', ['is_already_accepted' => $alreadyAccepted]);

            // Get DONOR's nearest center with coordinates (fetch regardless of acceptance status for consistent response)
            $donorNearestCenterDetails = null;
            if (is_numeric($personneDonor->latitude) && is_numeric($personneDonor->longitude)) {
                try {
                    $donorNearestCenterDetails = $this->getCentreProcheWithCoords($personneDonor->latitude, $personneDonor->longitude);
                    Log::info('AccepterDemande: Fetched nearest center for donor.', ['center_details' => $donorNearestCenterDetails]);
                } catch (\Exception $e) {
                    Log::warning('AccepterDemande: Error fetching nearest center for donor.', ['donor_id' => $donorId, 'error' => $e->getMessage()]);
                }
            } else {
                Log::warning('AccepterDemande: Donor has invalid coordinates for center calculation.', ['donor_id' => $donorId]);
            }


            if ($alreadyAccepted) {
                Log::info('AccepterDemande: Donor has already accepted this request.', ['donor_id' => $donorId, 'demande_id' => $demandeId]);
                return response()->json([
                    'success' => true,
                    'message' => 'This request has already been accepted by you.',
                    'already_accepted' => true, // Crucial flag
                    'demande_id' => $demande->id,
                    'center_name' => $donorNearestCenterDetails['name'] ?? null,
                    'center_latitude' => $donorNearestCenterDetails['latitude'] ?? null,
                    'center_longitude' => $donorNearestCenterDetails['longitude'] ?? null,
                    'requester_latitude' => $demande->latitude,
                    'requester_longitude' => $demande->longitude,
                ]);
            }

            Log::info('AccepterDemande: Attaching donor to demande via pivot table.');
            $personneDonor->demandes()->syncWithoutDetaching([
                $demandeId => ['date_acceptation' => now()]
            ]);
            Log::info('AccepterDemande: Donor attached to demande successfully.');
            Log::debug('AccepterDemande: Pivot table sync complete.');

            Log::debug('AccepterDemande: Preparing to send notifications.');
            $requesterPersonne = Personne::where('idUser', $demande->idDemandeur)->first();
            if (!$requesterPersonne) {
                Log::error('AccepterDemande: Requester (Personne) not found for notification.', ['idDemandeur' => $demande->idDemandeur]);
            } else {
                Log::info('AccepterDemande: Requester found for notification.', ['requester_id' => $requesterPersonne->idUser]);
                $notificationTitle = 'Blood Request Accepted!';
                $donorPseudo = $personneDonor->user->pseudo ?? ($personneDonor->pseudo ?? $personneDonor->idUser);
                $notificationBody = "Donor {$donorPseudo} has accepted your request for blood group {$demande->groupageDemande}.";

                $notificationDataToRequester = [
                    'type' => 'acceptance',
                    'requestId' => (string) $demande->id,
                    'donorId' => (string) $personneDonor->idUser,
                    'donorPseudo' => (string) $donorPseudo,
                    'donor_center_name' => $donorNearestCenterDetails['name'] ?? 'N/A',
                ];
                Log::info("AccepterDemande: Preparing to notify requester.", [
                    'requester_id' => $requesterPersonne->idUser,
                    'demande_id' => $demande->id,
                    'notification_data' => $notificationDataToRequester
                ]);
                Log::debug('AccepterDemande: FCM Notification to Requester - Title:', [$notificationTitle, 'Body:' => $notificationBody, 'Data:' => $notificationDataToRequester]);


                if (!empty($requesterPersonne->fcm_token)) {
                    try {
                        $this->firebaseService->sendNotification(
                            $requesterPersonne->fcm_token,
                            ['title' => $notificationTitle, 'body' => $notificationBody],
                            $notificationDataToRequester
                        );
                        Log::info("AccepterDemande: FCM acceptance notification sent to requester.", ['requester_id' => $requesterPersonne->idUser]);
                    } catch (\Exception $e) {
                        Log::error("AccepterDemande: Failed to send FCM acceptance to requester.", [
                            'requester_id' => $requesterPersonne->idUser,
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                    }
                } else {
                     Log::warning("AccepterDemande: Requester has no FCM token for acceptance notification.", ['requester_id' => $requesterPersonne->idUser]);
                }
                try {
                    broadcast(new BloodRequestEvent([
                        'user_id' => $demande->idDemandeur,
                        'message' => $notificationBody,
                        'data' => $notificationDataToRequester
                    ]));
                    Log::info("AccepterDemande: Acceptance event broadcast to requester.", ['requester_id' => $demande->idDemandeur]);
                } catch (\Exception $e) {
                    Log::error("AccepterDemande: Failed to broadcast acceptance event to requester.", [
                        'requester_id' => $demande->idDemandeur,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            Log::info('AccepterDemande: Process completed successfully.', ['donor_id' => $donorId, 'demande_id' => $demandeId]);
            return response()->json([
                'success' => true,
                'message' => 'Request accepted successfully. Requester has been notified.',
                'already_accepted' => false, // Explicitly false for a new acceptance
                'demande_id' => $demande->id,
                'center_name' => $donorNearestCenterDetails['name'] ?? null,
                'center_latitude' => $donorNearestCenterDetails['latitude'] ?? null,
                'center_longitude' => $donorNearestCenterDetails['longitude'] ?? null,
                'requester_latitude' => $demande->latitude,
                'requester_longitude' => $demande->longitude,
            ]);

        } catch (\Exception $e) {
            Log::error('AccepterDemande: Unhandled error accepting blood request.', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Server error while accepting request. Please try again later.',
            ], 500);
        }
    }


    public function getUserRequests(Request $request)
    {
        Log::info('Attempting to fetch all requests for user', $request->all());
        Log::debug('getUserRequests: Full request object:', $request->toArray());

        try {
            $validator = Validator::make($request->all(), [
                'idDemandeur' => 'required|integer',
            ]);

            if ($validator->fails()) {
                Log::warning('getUserRequests validation failed', ['errors' => $validator->errors(), 'request' => $request->all()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error: idDemandeur is required.',
                    'errors' => $validator->errors()
                ], 422);
            }
            Log::debug('getUserRequests: Validation passed for idDemandeur:', ['idDemandeur' => $request->input('idDemandeur')]);

            $demandes = Demande::where('idDemandeur', $request->input('idDemandeur'))
                ->orderBy('dateDemande', 'desc')
                ->get();

            Log::info('getUserRequests successful', ['count' => $demandes->count()]);
            Log::debug('getUserRequests: Demandes found (IDs):', $demandes->pluck('id')->toArray());
            // Log::debug('getUserRequests: Demandes found (Full):', $demandes->toArray()); // Uncomment if full details needed for debug

            return response()->json([
                'success' => true,
                'requests' => $demandes,
                'total' => $demandes->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching user requests: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Server error while fetching user requests.',
            ], 500);
        }
    }

    public function getRequestStatus(Request $request, $id) {
        Log::info('Attempting to get request status for demand_id: ' . $id, $request->all());
        Log::debug('getRequestStatus: Full request object:', $request->toArray(), ['demand_id_param' => $id]);
        try {
            $validator = Validator::make($request->all(), [
                'idDemandeur' => 'required|integer', // This implies the user making the request is the original requester
            ]);

            if ($validator->fails()) {
                Log::warning('GetRequestStatus validation failed', ['errors' => $validator->errors(), 'request' => $request->all()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error: idDemandeur is required.',
                    'errors' => $validator->errors()
                ], 422);
            }
            Log::debug('getRequestStatus: Validation passed for idDemandeur:', ['idDemandeur' => $request->input('idDemandeur')]);


            $demande = Demande::find($id);

            if (!$demande) {
                Log::warning('GetRequestStatus: Demande not found', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Request not found'
                ], 404);
            }
            Log::debug('getRequestStatus: Demande found:', $demande->toArray());


            if ($demande->idDemandeur != $request->input('idDemandeur')) {
                Log::warning('GetRequestStatus: Unauthorized access attempt', ['demande_idDemandeur' => $demande->idDemandeur, 'request_idDemandeur' => $request->input('idDemandeur')]);
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
                        'pseudo' => $personne->user->pseudo ?? ($personne->pseudo ?? 'N/A'),
                        'numeroTlp1' => $personne->numeroTlp1,
                        'date_acceptation' => \Carbon\Carbon::parse($personne->pivot->date_acceptation)->toDateTimeString(),
                    ];
                });
            Log::info('GetRequestStatus successful for demand_id: ' . $id, ['accepted_donors_count' => $acceptedDonorsDetails->count()]);
            Log::debug('getRequestStatus: Accepted donor details:', $acceptedDonorsDetails->toArray());


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
        Log::info('Attempting to update phone', $request->all());
        Log::debug('updatePhone: Full request object:', $request->toArray());
        $validator = Validator::make($request->all(), [
            'idPersonne' => 'required|string|exists:personnes,idUser',
            'numeroTlp1' => [
                'required',
                'string',
                \Illuminate\Validation\Rule::unique('personnes', 'numeroTlp1')->ignore($request->idPersonne, 'idUser'),
            ],
        ]);

        if ($validator->fails()) {
            Log::warning('Update phone validation failed', ['errors' => $validator->errors(), 'request' => $request->all()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        Log::debug('updatePhone: Validation passed for idPersonne:', ['idPersonne' => $request->idPersonne]);


        try {
            $personne = Personne::where('idUser', $request->idPersonne)->firstOrFail();
            Log::debug('updatePhone: Personne found for phone update:', $personne->toArray());
            $personne->numeroTlp1 = $request->numeroTlp1;
            $personne->save();
            Log::info('Phone updated successfully for personne_idUser: ' . $request->idPersonne);
            Log::debug('updatePhone: Personne after phone update:', $personne->toArray());


            return response()->json([
                'success' => true,
                'message' => 'Numéro de téléphone mis à jour avec succès.',
                'data' => $personne
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             Log::warning('Personne not found for phone update', ['idPersonne' => $request->idPersonne]);
             return response()->json([
                'success' => false,
                'message' => 'Personne not found.',
            ], 404);
        } catch (\Exception $e) {
             Log::error('Error updating phone for personne_idUser ' . $request->idPersonne . ': ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Server error while updating phone number.',
            ], 500);
        }
    }

    public function updateFcmToken(Request $request)
    {
        Log::info('Attempting to update FCM token', $request->all());
        Log::debug('updateFcmToken: Full request object:', $request->toArray());
        $request->validate([
            'idUser' => 'required|string|exists:personnes,idUser',
            'fcm_token' => 'required|string',
        ]);
        Log::debug('updateFcmToken: Validation passed for user:', ['idUser' => $request->idUser]);

        try {
            $personne = Personne::where('idUser', $request->idUser)->firstOrFail();
            Log::debug('updateFcmToken: Personne found for FCM token update:', $personne->toArray());
            $personne->fcm_token = $request->fcm_token;
            $personne->save();
            Log::info('FCM token updated successfully for user_id: ' . $request->idUser);
            Log::debug('updateFcmToken: Personne after FCM token update:', $personne->toArray());


            return response()->json([
                'success' => true,
                'message' => 'FCM token updated successfully'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Personne not found for FCM token update', ['idUser' => $request->idUser]);
             return response()->json([
                'success' => false,
                'message' => 'Personne not found for FCM token update.',
            ], 404);
        }catch (\Exception $e) {
            Log::error('Error updating FCM token for user_id ' . $request->idUser . ': ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Server error while updating FCM token.',
            ], 500);
        }
    }
}