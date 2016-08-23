@extends('layouts.app')

@section('content')
<div class="container" ng-app="ngEvents" ng-controller="EventsController as vm">
    <div class="row"
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Please select the event of your interest:</div>

                <div class="panel-body ng-cloak">
                    <div id="gmap" class="embed-responsive embed-responsive-16by9">
                      <ng-map zoom="12" center="[25.79, -80.18]" default-style="false">
                        <marker
                          on-click="vm.showEvent({| event.id |})"
                          ng-repeat="(id, event) in events"
                          draggable="false"
                          position="{| event.lat |}, {| event.lng |}"
                          title="{| event.name |}"></marker>
                      </ng-map>
                    </div>
                    <div class="row row-spacing">
                        <div class="col-xs-12 text-center disabled">
                            <a id="call-to-action" class="btn btn-danger btn-large btn-lg {| ev?'':'disabled' |}" ng-href="{{ URL::to('event') }}/{| ev.id |}">Book your place</a>
                        </div>
                    </div>
                    <div id="event-details" class="row row-spacing" ng-if="ev">
                        <img ng-src="{{ asset('pics/venue/full/200x200/') }}/{| ev.logo |}" class="ev-logo col col-md-3"/>
                        <div class="col col-md-9">
                            <h1><span class="ev-name">{| ev.name |}</span></h1>
                            <h3 class="ev-description">{| ev.description |}</h3>
                            <p>Starting at: <span class="ev-start-date">{| ev.start_date|fullDate |}</span>.</p>
                            <h2><span class="ev-venue-name">{| ev.venue |}</span></h2>
                            <p><span class="ev-address">{| ev.address |}</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content-js')
<script src="http://maps.google.com/maps/api/js?key=AIzaSyCk4hiZ8MGAsk1vGXtDZl1VpOPKm_oR8fs"></script>
<script src="{{ asset('lib/ng-map-1.17.3/ng-map.min.js') }}"></script>
<script src="{{ asset('lib/app/ngEvents.js') }}"></script>
<script>
// Workaround for silly bug on touch Chrome
navigator = navigator || {};
navigator.msMaxTouchPoints = navigator.msMaxTouchPoints || 2;
navigator.msPointerEnabled = true;
</script>
@endsection
