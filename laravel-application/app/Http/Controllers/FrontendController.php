<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Classes\Helper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

class FrontendController extends Controller
{
    public function home()
    {
        return view('home');
    }
}