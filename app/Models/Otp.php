<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 

use Vonage\Client;
use Vonage\Client\Credentials\Basic;
class Otp extends Model
{
    use HasFactory;

    /**
     * Les champs qui peuvent être remplis via un formulaire.
     *
     * @var array
     */
    protected $fillable = [
        'phone',        
        'otp',          
        'expires_at',  
    ];

    /**
     * Les types des colonnes (casting).
     *
     * @var array
     */
    protected $casts = [
        'expires_at' => 'datetime',
    ];


    public static function sendMessage($phone, $otp)
    {
          //TODO SERVICE SMS A ACHETER
        
        try {

            $basic  = new Basic(env('VONAGE_KEY'), env('VONAGE_SECRET'));
            $client = new Client($basic);

            
            $response = $client->sms()->send(
                new \Vonage\SMS\Message\SMS($phone, "213661845600", 'A text message sent using the Nexmo SMS API')
            );
            $sent = $response->current();

            if ($sent->getStatus() == 0) {
                $message=  "OTP envoyé avec succès.";
            } else {
                $message= "Message failed with status: " . $sent->getStatus();
            } 
            
            return [
                'status' => 'success',  
                'message' => $message,
                'otp' => $otp,
            ]; 
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Erreur lors de l\'envoi : ' . $e->getMessage(),
            ];
        }
    }
}
