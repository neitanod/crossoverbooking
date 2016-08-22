<?php

namespace App\Http\Controllers\API;

use App\Event;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\EventRepository;
use App\Repositories\StandRepository;

class EventsController extends Controller
{
    /**
     * Show the public events map.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $events = EventRepository::upcomingEventsList();
      return response()->json(['events'=>$events]);
    }

    /**
     * Show the selected event with it's stands.
     *
     * @return \Illuminate\Http\Response
     */
    public function event($id)
    {
      $event = EventRepository::eventDetails($id);
      $stands = StandRepository::forEvent($id);
        return response()->json(['event'=>$event,'stands'=>$stands]);
    }
}
