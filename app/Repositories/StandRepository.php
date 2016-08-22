<?php

namespace App\Repositories;

use DB;
use App\Stand;

class StandRepository
{
    public static function forEvent($event_id = null){
        if(empty($event_id)) return [];
        $stands = DB::table('venue_maps')->select(
              'venue_maps.id as venue_map_id',
              'venue_maps.event_id as event_id',
              'stands.id as stand_id',
              'stands.id_internal as id_internal',
              'companies.*',
              'stands.*',
              'companies.name as company',
              'companies.logo as company_logo')
          ->where('venue_maps.event_id',$event_id)
          ->leftJoin('stands','stands.venue_map_id','=','venue_maps.id')
          ->leftJoin('companies','stands.company_id','=','companies.id')
          ->get();
        $collection = collect($stands)->keyBy('id_internal');
        $collection_array = $collection->toArray();
        return $collection_array;
    }
    public static function findByInternalId($venue_map_id, $id_internal) {
      return Stand::where(
        ['venue_map_id'=> $venue_map_id, 'id_internal'=> $id_internal]
      )->first();
    }

}
