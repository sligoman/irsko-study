<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function setToken()
    {
        session(['security-token' => 'security']);

        return redirect()->route('home');
    }
}
