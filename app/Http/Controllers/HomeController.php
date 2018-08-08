<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Date\Date;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Date::setLocale('es_MX');
    }

    public function index()
    {

        return view('home');
    }
}
