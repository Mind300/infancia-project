<?php

namespace App\Http\Controllers\Api\Test;

use App\Http\Controllers\Controller;
use App\Notifications\SendmailNotification;
use Illuminate\Http\Request;

class TestController extends Controller
{
    //Test Mail
    public function sendmail(){
        $user = auth()->user()->id;
        $user->notify(SendmailNotification());
    }
}
