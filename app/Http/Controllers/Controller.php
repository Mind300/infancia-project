<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\DemoNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    
    public function demoMail(string $id){
        $user = User::find($id);
        $user->notifiy(new DemoNotification());
    }
}
