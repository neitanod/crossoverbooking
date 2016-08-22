<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class EventController extends Controller
{
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
    public function event($id)
    {
        return view('event',['event_id'=>$id]);
    }
}
