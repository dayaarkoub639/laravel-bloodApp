<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;


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
        return new Channel('blood-requests');// Canal public
    }

    public function broadcastAs()
    {
        return 'new-blood-request';// Nom de l'événement
    }
}
