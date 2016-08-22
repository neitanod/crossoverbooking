<?php

namespace App\Http\Controllers\API;

use App\Event;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\EventRepository;
use App\Repositories\StandRepository;
use App\Repositories\DocumentRepository;

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
      // Avoid unnecesary joins and row duplications
      $event = EventRepository::eventDetails($id);
      $stands = StandRepository::forEvent($id);

      // Get all needed documents with one performant query
      $company_ids = array_unique(array_map(function($stand){ return $stand->company_id; }, $stands));
      $documents = DocumentRepository::forCompanyArray($company_ids);
      foreach($stands as $k => $stand){
        $company_id = $stand->company_id;
        $stands[$k]->documents = array_filter($documents,
          function($doc) use ($company_id) {
            if($doc->company_id == $company_id) return $doc;
          });
      }
        return response()->json(['event'=>$event,'stands'=>$stands]);
    }
}
