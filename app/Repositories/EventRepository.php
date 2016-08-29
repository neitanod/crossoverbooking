<?php

namespace App\Repositories;

use DB;

class EventRepository
{
    public static function upcomingEventsList($date = null){
        if(is_null($date)) $date = date('Y-m-d');
        $events = DB::table('events')->select('venues.*','events.*', 'venues.name as venue')->where('end_date','>',$date)
          ->leftJoin('venues','events.venue_id','=','venues.id')
          ->get();
        $collection = collect($events)->keyBy('id');
        $collection->toArray();
        return $collection;
    }

    public static function eventDetails($id){
        $event = DB::table('events')->select('venue_maps.*','venues.*','events.*', 'venue_maps.name as map', 'venues.name as venue')->where('events.id',$id)
          ->leftJoin('venues','events.venue_id','=','venues.id')
          ->leftJoin('venue_maps','venue_maps.event_id','=','events.id')
          ->get();
        return empty($event[0])?[]:(array)$event[0];
    }

    public static function getRecentEvents($date = null)
    {
        $date = is_null($date)?date('Y-m-d',strtotime('yesterday')):$date;
        $events = DB::table('events')->select('companies.*','events.name AS event_name')->where('events.end_date',$date)
          ->leftJoin('venue_maps','venue_maps.event_id','=','events.id')
          ->leftJoin('stands','stands.venue_map_id','=','venue_maps.id')
          ->leftJoin('companies','companies.id','=','stands.company_id')
          ->get();
        return $events;
    }
}
