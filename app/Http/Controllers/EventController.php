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
        $events = Event::find($event_id);
        if(!$events) return view('errors.404');
        $event = $events->first();
        if(!$event) return view('errors.404');
        $venues = Venue::find($event->venue_id);
        if(!$venues) return view('errors.404');
        $venue = $venues->first();
        if(!$venue) return view('errors.404');
        $venue_maps = VenueMap::where(['event_id'=>$event_id]);
        if(!$venue_maps) return view('errors.404');
        $venue_map = $venue_maps->first();
        if(!$venue_map) return view('errors.404');
        $stand = StandRepository::findByInternalId($venue_map->id, $stand_id_internal);
        if(!$stand) return view('errors.404');
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
