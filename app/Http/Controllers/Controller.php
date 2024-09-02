<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\ApproveNotification;
use App\Notifications\DemoNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Password;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function demoMail()
    {
        $user = User::find(2);
        $token = Password::createToken($user);
        $user->notify(new ApproveNotification($user, $token, 'accept'));
    }
}
