<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\AndroidConfig; // Keep this
use Kreait\Firebase\Messaging\ApnsConfig;    // Keep this
use Kreait\Firebase\Messaging\Notification;  // Keep this

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
            // It might be good to re-throw or handle this more gracefully
            // if messaging cannot be initialized, as the service becomes unusable.
        }
    }
    
    /**
     * Send a FCM notification to a single device
     *
     * @param string $token The FCM token of the device
     * @param array $notificationData Notification data (title, body) // Renamed for clarity
     * @param array $data Custom data payload
     * @return string|null The message ID if successful, null otherwise
     */
    public function sendNotification(string $token, array $notificationData, array $data = [])
    {
        // Ensure messaging is initialized
        if (!$this->messaging) {
            Log::error('FCM send error: Firebase Messaging not initialized.');
            return null;
        }

        try {
            if (empty($token)) {
                Log::warning('Attempted to send FCM with empty token');
                return null;
            }
            
            // Create the notification
            $fcmNotification = Notification::create($notificationData['title'] ?? '', $notificationData['body'] ?? '');
            
            // Create the message
            $message = CloudMessage::withTarget('token', $token)
                ->withNotification($fcmNotification)
                ->withData($data);
                
            // Add Android specific configuration
            // --- CORRECTED ANDROID CONFIG ---
            $androidConfig = AndroidConfig::fromArray([
                'priority' => 'high', // Set priority here
                'notification' => [
                    'channel_id' => 'blood_donation_channel',
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    // You can add other Android specific notification properties here
                    // e.g., 'sound' => 'default', 'icon' => 'your_icon_name'
                ],
                // You can also set TTL for Android if needed:
                // 'ttl' => '3600s', 
            ]);
            // --- END OF CORRECTION ---
                
            $message = $message->withAndroidConfig($androidConfig);
            
            // Add Apple specific configuration
            // Consider adding 'priority' => '10' for APNS high priority if needed
            $apnsConfig = ApnsConfig::fromArray([ // Using fromArray for consistency
                'headers' => [ // Headers for APNS
                    'apns-priority' => '10', // 10 for high, 5 for normal
                ],
                'payload' => [ // Payload wrapper for APNS
                    'aps' => [
                        'sound' => 'default',
                        'badge' => 1, // Consider making badge count dynamic or removing if not used
                        'content-available' => 1, // For background updates
                        // 'alert' => [ // If you want to structure the alert itself
                        //     'title' => $notificationData['title'] ?? '',
                        //     'body' => $notificationData['body'] ?? '',
                        // ]
                    ],
                ],
            ]);
                
            $message = $message->withApnsConfig($apnsConfig);
            
            // Send the message
            $response = $this->messaging->send($message);
            
            Log::info('FCM notification sent successfully', [
                'token' => $token,
                // $response is often an array with message_id or a string directly
                // Check the type of $response from $this->messaging->send() documentation for your SDK version
                'response_data' => $response, 
            ]);
            
            // Extract message ID if $response is an array (common for single sends)
            // This depends on the exact structure returned by your SDK version
            // Example for some versions: return $response['name'] ?? ($response[0]['name'] ?? null);
            // For now, returning the whole response might be okay for logging, but for consistent return, check SDK
            if (is_array($response) && isset($response['name'])) { // kreait/firebase-php ^5.x used 'name' for message ID
                 return $response['name'];
            } elseif (is_string($response)) { // Some contexts might return string ID directly
                 return $response;
            }
            // If response structure is different for your SDK version, adjust extraction of message ID
            Log::warning('FCM response format unexpected.', ['response' => $response]);
            return null; // Or return a more specific part of the response if known

        } catch (\Kreait\Firebase\Exception\MessagingException $e) { // More specific catch
            Log::error('FCM MessagingException: ' . $e->getMessage(), [
                'token' => $token,
                'notification' => $notificationData,
                'data' => $data,
                'errors' => method_exists($e, 'errors') ? $e->errors() : 'N/A',
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('FCM general send error: ' . $e->getMessage(), [
                'token' => $token,
                'notification' => $notificationData,
                'data' => $data,
                'trace' => $e->getTraceAsString(), // More detailed trace for general errors
            ]);
            return null;
        }
    }
    
    /**
     * Send multiple FCM notifications to a list of devices
     *
     * @param array $tokens List of FCM tokens
     * @param array $notificationData Notification data (title, body) // Renamed
     * @param array $data Custom data payload
     * @return array List of results (could be message IDs or nulls)
     */
    public function sendMulticastNotification(array $tokens, array $notificationData, array $data = [])
    {
        // Ensure messaging is initialized
        if (!$this->messaging) {
            Log::error('FCM send error: Firebase Messaging not initialized for multicast.');
            return array_fill_keys($tokens, null); // Return nulls for all tokens
        }

        $results = [];
        $messages = [];

        if (empty($tokens)) {
            Log::warning('Attempted to send FCM multicast with empty tokens list');
            return [];
        }

        // Create the common notification and configurations
        $fcmNotification = Notification::create($notificationData['title'] ?? '', $notificationData['body'] ?? '');
        $androidConfig = AndroidConfig::fromArray([
            'priority' => 'high',
            'notification' => [
                'channel_id' => 'blood_donation_channel',
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            ],
        ]);
        $apnsConfig = ApnsConfig::fromArray([
            'headers' => ['apns-priority' => '10'],
            'payload' => ['aps' => ['sound' => 'default', 'badge' => 1, 'content-available' => 1]],
        ]);

        foreach ($tokens as $token) {
            if (empty($token)) {
                Log::warning('Skipping empty token in multicast send.');
                $results[$token] = null; // Or just skip adding to results
                continue;
            }
            $messages[] = CloudMessage::withTarget('token', $token)
                            ->withNotification($fcmNotification)
                            ->withData($data)
                            ->withAndroidConfig($androidConfig)
                            ->withApnsConfig($apnsConfig);
        }

        if (empty($messages)) {
            return [];
        }

        try {
            $report = $this->messaging->sendAll($messages);
            
            Log::info('FCM multicast report', [
                'successful_sends' => $report->successes()->count(),
                'failed_sends' => $report->failures()->count(),
            ]);

            // Process successes
            foreach ($report->successes()->getItems() as $success) {
                // The target is often part of the success item, allowing you to map back to the token
                $targetToken = $success->target() ? $success->target()->value() : 'unknown_token_success';
                $results[$targetToken] = $success->messageId() ?? true; // Store messageId or generic success
            }

            // Process failures
            foreach ($report->failures()->getItems() as $failure) {
                $targetToken = $failure->target() ? $failure->target()->value() : 'unknown_token_failure';
                Log::error('FCM multicast send failure for token: ' . $targetToken, [
                    'error_reason' => $failure->error() ? $failure->error()->getMessage() : 'Unknown reason',
                    'message_snapshot' => $failure->message() ? $failure->message()->jsonSerialize() : null,
                ]);
                $results[$targetToken] = null;
            }
        } catch (\Kreait\Firebase\Exception\MessagingException $e) {
            Log::error('FCM Multicast MessagingException: ' . $e->getMessage(), [
                'tokens_count' => count($tokens),
                'errors' => method_exists($e, 'errors') ? $e->errors() : 'N/A',
            ]);
            // Mark all as failed in this case
            foreach ($tokens as $token) {
                if (!isset($results[$token])) $results[$token] = null;
            }
        } catch (\Exception $e) {
            Log::error('FCM general multicast send error: ' . $e->getMessage(), [
                'tokens_count' => count($tokens),
                'trace' => $e->getTraceAsString(),
            ]);
            foreach ($tokens as $token) {
                if (!isset($results[$token])) $results[$token] = null;
            }
        }
        
        return $results;
    }
}