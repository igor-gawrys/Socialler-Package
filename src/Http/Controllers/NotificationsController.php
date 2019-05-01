<?php

namespace Igorgawrys\Socialler\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\User;
use Carbon\Carbon;
class NotificationsController extends Controller
{
    public function store(User $user,Request $request){
    $user->notify(new \App\Notifications\Notify($request->input('content')));
     return response()->json(["message" => "Notification send."]);
    }
}
