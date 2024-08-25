<?php

namespace App\Http\Controllers\Api\Test;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\ApproveNotification;
use App\Notifications\DemoNotification;
use App\Notifications\SendmailNotification;
use Illuminate\Http\Request;

class TestController extends Controller
{
    // //Test Mail
    // public function sendmail(){
    //     $user = auth()->user()->id;
    //     $user->notify(SendmailNotification());
    // }

    public function sendmail(){
        $user = User::find(1);
        $user->notify(new ApproveNotification('Pending'));
    }
}
