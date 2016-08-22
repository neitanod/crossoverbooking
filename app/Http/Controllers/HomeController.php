<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the public events map.
     *
     * @return \Illuminate\Http\Response
     */
    public function events()
    {
        return view('events');
    }

    /**
     * Show the selected event with it's stands.
     *
     * @return \Illuminate\Http\Response
     */
    public function event()
    {
        return view('event');
    }
}
