<?php

namespace App\Http\Controllers;

use App\Event;
use App\Venue;
use App\VenueMap;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Repositories\StandRepository;


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

    public function book($event_id, $stand_id_internal)
    {
        $event = Event::find($event_id)->first();
        $venue = Venue::find($event->venue_id)->first();
        $venue_map = VenueMap::where(['event_id'=>$event_id])->first();
        $stand = StandRepository::findByInternalId($venue_map->id, $stand_id_internal);
        return view('book',
          [
            'event'=>$event,
            'stand'=>$stand,
            'venue'=>$venue,
            'venue_map'=>$venue_map,
            'event_id'=>$event_id,
            'stand_id_internal'=>$stand_id_internal
          ]);
    }
}
