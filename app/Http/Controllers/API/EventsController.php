<?php

namespace App\Http\Controllers\API;

use App\Event;
use App\Company;
use App\VenueMap;
use App\Stand;
use App\Document;
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

    public function reserve(Request $request){
      $reservation = $request->toArray();
      $reservation = $reservation['reservation'];
      $company = new Company();
      $company->name = $reservation['company_name'];
      $company->logo = $reservation['company_logo'];
      $company->admin_name = $reservation['admin_name'];
      $company->admin_email = $reservation['admin_email'];
      $company->phone = $reservation['phone'];
      $company->email = $reservation['email'];
      $company->website = $reservation['website'];
      $company->facebook = $reservation['facebook'];
      $company->twitter = $reservation['twitter'];
      $company->save();
      $documents = empty($reservation['documents'])?[]:$reservation['documents'];
      foreach($documents as $doc){
        $document = new Document();
        $document->company_id = $company->id;
        $document->file = $doc['filename'];
        $document->title = empty($doc['title'])?"Document":$doc['title'];
        $document->save();
      }
      $venue_map = VenueMap::where([
        'event_id'=>$reservation['event_id'],
      ])->first();
      $stand = Stand::where([
        'id_internal'=>$reservation['stand_id_internal'],
        'venue_map_id'=>$venue_map['id'],
      ])->first();
      $stand->status ='reserved';
      $stand->company_id =$company->id;
      $stand->save();
      return response()->json(['status'=>'success']);
    }
}
