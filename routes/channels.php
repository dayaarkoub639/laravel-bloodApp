<?php
use Illuminate\Support\Facades\Broadcast;
/*
Broadcast::channel('blood-requests', function ($user) {
    return true; // Modifier cette logique selon tes besoins
});*/
Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});