<?php

namespace App\Http\Controllers;

use App\Models\Nurseries;
use App\Models\User;
use App\Notifications\RegitserNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function demoMail()
    {
        $user = Nurseries::find(1);
        $user->notify(new RegitserNotification());
    }
}
