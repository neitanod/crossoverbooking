@extends('layouts.app')

@section('content')
<script>
  top.EVENT_ID = {{ $event_id }};
  top.STAND_ID_INTERNAL = {{ $stand_id_internal }};
</script>
<div class="container" ng-app="ngBookStand" ng-controller="BookStandController">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default ng-cloak">
                <div class="panel-heading">{{ $event->name }}</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <img ng-src="{{ asset('pics/stand/full/800x600/') }}/{{ $stand->picture }}" class="pull-right stand-picture-big col col-sm-6 img-responsive"/>
                            <h4>Starting: {{ date('D, F d, Y',strtotime($event->start_date)) }}</h4>
                            <h4>Until: {{ date('D, F d, Y',strtotime($event->end_date)) }}</h4>
                            <p>{{ $venue['name'] }}<br/>{{ $venue_map['name'] }}<br/></p>
                            <h3>Stand {{ $stand['id_internal'] }}</h3>
                            <h4>Current status: {{ $stand['status'] }}</h4>
                            @if($stand->status == 'available')
                            <h4>Current price: ${{ $stand->price }}</h4>
                            @endif

                        </div>
                        <div class="col-xs-10 col-xs-offset-1">
                            @if($stand->status == 'available')

                            <div class="row">

                              <div class="form-group row row-spacing">
                                <label for="f_c_logo" class="col-xs-3 col-form-label">Company logo</label>
                                <div class="col-xs-9">
                                  <input id="f_c_logo" type="text" class="form-control hidden" ng-model="res.company_logo">
                                  <div id="has_logo" ng-show="res.company_logo" ng-cloak>
                                    <img ng-src="{{ URL::to('/pics/company/full/800x600') }}/{| res.company_logo |}" style="max-width: 300px">
                                    <span class="btn btn-danger" ng-click="company_logo_reset()">Change</span>
                                  </div>
                                  <div id="needs_logo" ng-show="!res.company_logo" ng-cloak>
                                    @include('single_file_uploader',['id'=>'logo', 'target_uri'=>'/upload/companylogo', 'accept'=>'image/jpeg,image/png'])
                                  </div>
                                </div>
                              </div>

                            <form method="post" id="form-reservation">

                              <div class="form-group row row-spacing">
                                <input id="f_s_id" type="hidden" ng-model="res.stand_id_internal" value="{{ $stand->id }}">
                                <label for="f_c_name" class="col-xs-3 col-form-label">Company name</label>
                                <div class="col-xs-9">
                                  <input id="f_c_name" type="text" ng-model="res.company_name" value="" class="form-control" required="">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="f_c_admin_name" class="col-xs-3 col-form-label">Contact person's name</label>
                                <div class="col-xs-9">
                                  <input id="f_c_admin_name" type="text" ng-model="res.admin_name" value="" class="form-control">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="f_c_admin_email" class="col-xs-3 col-form-label">Contact person's email</label>
                                <div class="col-xs-9">
                                  <input id="f_c_admin_email" type="email" ng-model="res.admin_email" value="" class="form-control">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="f_c_phone" class="col-xs-3 col-form-label">Company's contact phone number</label>
                                <div class="col-xs-9">
                                  <input id="f_c_phone" type="text" ng-model="res.phone" value="" class="form-control">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="f_c_email" class="col-xs-3 col-form-label">Company's contact email address</label>
                                <div class="col-xs-9">
                                  <input id="f_c_email" type="email" ng-model="res.email" value="" class="form-control">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="f_c_website" class="col-xs-3 col-form-label">Company's Website URL</label>
                                <div class="col-xs-9">
                                  <input id="f_c_website" type="text" ng-model="res.website" value="" class="form-control">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="f_c_facebook" class="col-xs-3 col-form-label">Company's Facebook account</label>
                                <div class="col-xs-9">
                                  <input id="f_c_facebook" type="text" ng-model="res.facebook" value="" class="form-control">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="f_c_twitter" class="col-xs-3 col-form-label">Company's Twitter account</label>
                                <div class="col-xs-9">
                                  <input id="f_c_twitter" type="text" ng-model="res.twitter" value="" class="form-control">
                                </div>
                              </div>



                            </form>

                              <div class="form-group row row-spacing">
                                <label for="f_c_twitter" class="col-xs-3 col-form-label">Marketing documents</label>
                                <div class="col-xs-9">
                                  <div class="row row-spacing" ng-repeat='doc in res.documents'>
                                    <div class="col col-xs-12">
                                      <label>Document Title: </label>
                                    </div>
                                    <div class="col col-xs-12 row row-spacing">
                                      <div class="col col-xs-8">
                                        <input type='text' ng-model="doc.title" class="form-control">
                                      </div>
                                      <div class="col col-xs-2">
                                        <a class="btn btn-primary" href="{{ URL::to('/documents/') }}/{|doc.filename|}" download>
                                          <i class="fa fa-file"></i> file
                                        </a>
                                      </div>
                                      <div class="col col-xs-2">
                                        <span class="btn btn-danger" ng-click="remove_document(doc)">
                                          <i class="fa fa-cross"></i> delete
                                        </span>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-xs-12 row-spacing" ng-show="show_doc_uploader">
                                    @include('single_file_uploader',['id'=>'doc', 'target_uri'=>'/upload/document', 'accept2'=>'application/pdf,image/jpeg,image/png'])
                                  </div>
                                </div>
                                <div class="col-xs-12 col-offset-3 row-spacing">
                                <span class="btn btn-primary pull-right" ng-click="start_doc_uploader()">Add a marketing document</span>
                                </div>
                              </div>

                              <div class="form-group row row-spacing">
                                <span class='btn btn-primary btn-lg btn-large pull-right' ng-click="makeReservation()">Confirm reservation</span>
                              </div>

                            </div>

                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content-js')
<script src="{{ asset('lib/app/ngBookStand.js') }}"></script>
<script src="{{ asset('lib/bootstrap3-dialog-1.34.7/bootstrap-dialog.min.js') }}"></script>
<link  href="{{ asset('lib/bootstrap3-dialog-1.34.7/bootstrap-dialog.min.css') }}" type="text/css" rel="stylesheet" />
<script src="{{ asset('lib/single-bootstrap-uploader/SingleBootstrapUploader.js') }}"></script>
@endsection
