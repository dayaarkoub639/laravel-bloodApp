<?php
namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\Personne;
use Illuminate\Http\Request;

use App\Constants\AuthConstants;



use App\Http\Traits\HttpResponses;
use App\Models\User;


use App\Models\Otp;
use Illuminate\Http\JsonResponse;
use App\Services\InfobipService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
class ApiOtpController extends Controller
{
    protected $infobipService;
    public function __construct(InfobipService $infobipService)
    {
        $this->infobipService = $infobipService;
    }

    /**
     * Envoyer un OTP au numéro de téléphone
     */
    public function sendOtp(Request $request)
    {
        try {


            $request->validate([
                'phone' => 'required|string'
            ]);
            $otp =  random_int(0000, 9999);
            $expiresAt = Carbon::now()->addMinutes(10); // OTP validity: 10 minutes

            // Save OTP
            Otp::updateOrCreate(
                ['phone' => $request->phone],
                ['otp' => $otp, 'expires_at' => $expiresAt]
            );

          // Send OTP (SMS )  //TODO SERVICE SMS A ACHETER 
           $response = $this->infobipService->sendSms($request->phone,   "VOTRE OTP : $otp"); 
            
            // Retournez la réponse JSON
            return response()->json($response);
        
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de l\'envoi de l\'OTP.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


   public function verifyOtp(Request $request) {
    try{
        $request->validate([
            'phone' => 'required|string',
            'otp' => 'required|string'
        ]);

        $otpRecord = Otp::where('phone', $request->phone)
            ->where('otp', $request->otp)
            ->where('expires_at', '>=', Carbon::now())
            ->first();

        if (!$otpRecord) {
            return response()->json(['success'=>false,'message' => 'Invalid or expired OTP'], 400);
        }


        $phoneExists = Personne::where('numeroTlp1', $request->phone)->exists();


        return response()->json([ 'success'=>true, 'phoneExists'=>$phoneExists, 'message' => 'OTP verified successfully']);
    } catch (\Exception $e) {
        return response()->json([
            'success'=>false,
            'message' => 'Une erreur est survenue lors de la vérification de l\'OTP.',
            'error' => $e->getMessage(),
        ], 500);
    }
    }


}
