<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WalletAccountTemporalController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('globalImages', 'userRoles');
        return Inertia::render('App/Member/DigitalCards', [
            'user' => $user
        ]);
    }
}
