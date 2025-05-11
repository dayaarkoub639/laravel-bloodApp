<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Firebase\Messaging\Notification;

class FirebaseService
{
    protected $messaging;
    
    public function __construct()
    {
        try {
            $factory = (new Factory)
                ->withServiceAccount(config('firebase.credentials'));
            
            $this->messaging = $factory->createMessaging();
        } catch (\Exception $e) {
            Log::error('Firebase initialization error: ' . $e->getMessage());
        }
    }
    
    /**
     * Send a FCM notification to a single device
     *
     * @param string $token The FCM token of the device
     * @param array $notification Notification data (title, body)
     * @param array $data Custom data payload
     * @return string|null The message ID if successful, null otherwise
     */
    public function sendNotification(string $token, array $notification, array $data = [])
    {
        try {
            if (empty($token)) {
                Log::warning('Attempted to send FCM with empty token');
                return null;
            }
            
            // Create the notification
            $fcmNotification = Notification::create($notification['title'] ?? '', $notification['body'] ?? '');
            
            // Create the message
            $message = CloudMessage::withTarget('token', $token)
                ->withNotification($fcmNotification)
                ->withData($data);
                
            // Add Android specific configuration
            $androidConfig = AndroidConfig::new()
                ->withHighPriority()
                ->withNotification([
                    'channel_id' => 'blood_donation_channel',
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                ]);
                
            $message = $message->withAndroidConfig($androidConfig);
            
            // Add Apple specific configuration
            $apnsConfig = ApnsConfig::new()
                ->withApsDictionary([
                    'aps' => [
                        'sound' => 'default',
                        'badge' => 1,
                        'content-available' => 1,
                    ],
                ]);
                
            $message = $message->withApnsConfig($apnsConfig);
            
            // Send the message
            $response = $this->messaging->send($message);
            
            Log::info('FCM notification sent successfully', [
                'token' => $token,
                'messageId' => $response,
            ]);
            
            return $response;
        } catch (\Exception $e) {
            Log::error('FCM send error: ' . $e->getMessage(), [
                'token' => $token,
                'notification' => $notification,
                'data' => $data,
            ]);
            
            return null;
        }
    }
    
    /**
     * Send multiple FCM notifications to a list of devices
     *
     * @param array $tokens List of FCM tokens
     * @param array $notification Notification data (title, body)
     * @param array $data Custom data payload
     * @return array List of message IDs for successful sends
     */
    public function sendMulticastNotification(array $tokens, array $notification, array $data = [])
    {
        $results = [];
        
        foreach ($tokens as $token) {
            $result = $this->sendNotification($token, $notification, $data);
            if ($result) {
                $results[] = $result;
            }
        }
        
        return $results;
    }
}