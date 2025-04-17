<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\PrivateChannel;

class BloodRequestEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets,SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function broadcastOn()
    {
       
       // return new Channel('user.11');// Canal public
        // Utilisation d'un canal privé basé sur l'ID de l'utilisateur
        return new PrivateChannel('user.' . $this->data['user_id']);
    }

    public function broadcastAs()
    {
        return 'new-blood-request';// Nom de l'événement
    }
}

