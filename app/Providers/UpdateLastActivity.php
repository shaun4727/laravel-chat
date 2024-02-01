<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Authenticated;
use App\Models\User;
use Carbon\Carbon;


class UpdateLastActivity extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->updateUserActivity();
    }

    private function updateUserActivity()
    {
        Event::listen(Authenticated::class, function ($event) {
            // Access the authenticated user here
            $user = $event->user;
            $cUser = User::find($user->id);
            if ($cUser) {
                $utc = Carbon::now('UTC');
                $utcBD = $utc->copy()->timezone('Asia/Dhaka');
                $cUser->last_activity = $utcBD;
                $cUser->save();
            }



        });


    }
}
