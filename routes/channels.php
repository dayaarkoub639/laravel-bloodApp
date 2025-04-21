<?php
use Illuminate\Support\Facades\Broadcast;
 
/*
Broadcast::channel('blood-requests', function ($user) {
    return true; // Modifier cette logique selon tes besoins
});*/
 
Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->idUser === (int) $userId;
  });