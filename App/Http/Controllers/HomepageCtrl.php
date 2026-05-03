<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomepageCtrl extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function Homepage(){
        return view('homepage');
    }
}
