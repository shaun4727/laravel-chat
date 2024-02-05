<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Messages;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('chats', function ($user) {

    return $user;
});
// Broadcast::channel('chats.{receiver}', function ($user,$receiver) {

//     return $user->id == $receiver;
// });


Broadcast::channel('MyChannel', function ($user) {

    return $user;
});
