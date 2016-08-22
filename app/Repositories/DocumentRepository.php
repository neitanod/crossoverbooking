<?php

namespace App\Repositories;

use DB;

class DocumentRepository
{
    public static function forCompanyArray($company_ids = null){
        if(empty($company_ids) || !is_array($company_ids)) return [];
        $documents = DB::table('documents')
          ->whereIn('company_id', $company_ids)
          ->get();
        $collection = collect($documents)->keyBy('id');
        $collection_array = $collection->toArray();
        return $collection_array;
    }

}
