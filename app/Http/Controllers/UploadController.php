<?php

namespace App\Http\Controllers;

use App\Event;
use App\Venue;
use App\VenueMap;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Repositories\StandRepository;
use Validator;
use Session;


class UploadController extends Controller
{
    /**
     * Receives upload files
     *
     * @return \Illuminate\Http\Response
     */
    public function receive($target, Request $request)
    {
      $targets =
        [
          'companylogo'=> [
            'destination_path' => dirname(dirname(dirname(dirname(__FILE__)))).'/public/pics/company/full/800x600/',
          ],
          'document'=> [
            'destination_path' => dirname(dirname(dirname(dirname(__FILE__)))).'/public/documents/',
          ]
        ];

      $destinationPath = $targets[$target]['destination_path']; // upload path

  $file_r = $request->file('input_file');
  $file = array('file' => $file_r);
  $rules = array('file' => 'mimes:jpeg,bmp,png,pdf'); //and for max size max:10000
  $validator = Validator::make($file, $rules);
  if ($validator->fails()) {
    return response()->json(['target'=>$target,'error'=>'Invalid file','data'=>[]]);
  }
  else {
    if ($file_r->isValid()) {
      $extension = $file_r->getClientOriginalExtension(); // getting image extension
      $fileName = $file_r->hashName();
      $file_r->move($destinationPath, $fileName); // uploading file to given path
      return response()->json(['target'=>$target,'data'=>['filename'=>$fileName]]);
    }
    else {
      return response()->json(['target'=>$target,'error'=>'Invalid file','data'=>[]]);
    }
  }














        ;
    }
}
