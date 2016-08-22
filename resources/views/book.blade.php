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

                            <form method="post" id="form-reservation" data-parsley-validate="">

                              <div class="form-group row row-spacing">
                                <input id="f_s_id" type="hidden" name="s[id]" value="{{ $stand->id }}">
                                <input id="csrf_token" type="hidden" name="_token" value="{{ csrf_token() }}">
                                <label for="f_c_name" class="col-xs-3 col-form-label">Company name</label>
                                <div class="col-xs-9">
                                  <input id="f_c_name" type="text" name="c[name]" value="" class="form-control" required="">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="f_c_logo" class="col-xs-3 col-form-label">Company logo</label>
                                <div class="col-xs-9">
                                  <input id="f_c_logo" type="hidden" name="c[logo]" value="" class="form-control">

                                  @require('file_uploader')

                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="f_c_admin_name" class="col-xs-3 col-form-label">Contact person's name</label>
                                <div class="col-xs-9">
                                  <input id="f_c_admin_name" type="text" name="c[admin_name]" value="" class="form-control">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="f_c_admin_email" class="col-xs-3 col-form-label">Contact person's email</label>
                                <div class="col-xs-9">
                                  <input id="f_c_admin_email" type="email" name="c[admin_email]" value="" class="form-control">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="f_c_phone" class="col-xs-3 col-form-label">Company's contact phone number</label>
                                <div class="col-xs-9">
                                  <input id="f_c_phone" type="text" name="c[phone]" value="" class="form-control">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="f_c_email" class="col-xs-3 col-form-label">Company's contact email address</label>
                                <div class="col-xs-9">
                                  <input id="f_c_email" type="email" name="c[email]" value="" class="form-control">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="f_c_website" class="col-xs-3 col-form-label">Company's Website URL</label>
                                <div class="col-xs-9">
                                  <input id="f_c_website" type="text" name="c[website]" value="" class="form-control">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="f_c_facebook" class="col-xs-3 col-form-label">Company's Facebook account</label>
                                <div class="col-xs-9">
                                  <input id="f_c_facebook" type="text" name="c[facebook]" value="" class="form-control">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="f_c_twitter" class="col-xs-3 col-form-label">Company's Twitter account</label>
                                <div class="col-xs-9">
                                  <input id="f_c_twitter" type="text" name="c[twitter]" value="" class="form-control">
                                </div>
                              </div>


                              <div class="form-group row">
                                <label for="f_c_twitter" class="col-xs-3 col-form-label">Marketing documents</label>
                                <div class="col-xs-9">
                                  @require('file_uploader')
                                <span class="btn btn-primary">Add a document</span>
                                </div>
                              </div>

                              <div class="form-group row">
                                <span class='btn btn-primary btn-lg btn-large pull-right' ng-click="makeReservation()">Confirm reservation</span>
                              </div>

                            </form>
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
<script src="{{ asset('lib/parsley-2.4.4/parsley.min.js') }}"></script>
@endsection
