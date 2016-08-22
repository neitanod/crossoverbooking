@extends('layouts.app')

@section('content')
<script>
  top.EVENT_ID = {{ $event_id }};
</script>
<div class="container" ng-app="ngEventDetails" ng-controller="EventDetailsController">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default ng-cloak">
                <div class="panel-heading">{| event.venue |}</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <h3>{| event.map |}</h3>
                            <h4>Select your desired location</h4>
                            <div id="svgdata" ng-bind-html="trusted.svgdata">
                            </div>
                            <div id="stand-details" class="row row-spacing" ng-if="selected.id">
                                <img ng-src="{{ asset('pics/stand/full/800x600/') }}/{| selected.picture |}" class="stand-picture col col-sm-4 img-responsive"/>
                                <div ng-if="selected.status=='available'" class="col col-sm-8">
                                    <a id="call-to-action" class="btn btn-danger btn-large btn-lg pull-right" ng-href="{{ URL::to('stand') }}/{| ev.id |}">Book this stand</a>
                                    <h2>Stand {| selected.id_internal |}</h2>
                                    <h1>Price: <span class="stand-price">$ {| selected.price|number:0 |}</span></h1>
                                </div>
                                <div ng-if="selected.status=='reserved'" class="col col-sm-8">
                                    <div class="stand-company pull-left">
                                        Reserved by:
                                        <h3>{| selected.company |}</h3>
                                    </div>
                                        <img ng-src="{{ asset('pics/company/full/800x600/') }}/{| selected.company_logo |}" class="pull-right stand-company-logo"/>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content-js')
<script src="{{ asset('lib/app/ngEventDetails.js') }}"></script>
@endsection
