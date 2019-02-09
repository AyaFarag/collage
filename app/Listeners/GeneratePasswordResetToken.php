<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GeneratePasswordResetToken
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $user = $event -> user;
        $passwordReset = $user -> passwordReset;
        $randomString = str_random(50);
        if (is_null($passwordReset))
            $user -> passwordReset() -> create(["token" => bcrypt($randomString)]);
        else
            $user -> passwordReset() -> update(["token" => bcrypt($randomString)]);
        $user -> phone_confirmation_code = null;
        $user -> save();
        return $randomString;
    }
}
