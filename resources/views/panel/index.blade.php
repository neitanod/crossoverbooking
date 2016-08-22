@extends('panel.frame')

@section('body')

  <div class="main_container" id="control-panel">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="{{ URL::to('/') }}" class="site_title">
                <img src="{{ URL::to('images/crossover-for-work-squarelogo.png') }}" style="margin: 1px 6px; height: 32px;"/> <span>Control Panel</span></a>
            </div>

            <div class="clearfix"></div>

            <br />

            <!-- sidebar menu -->
            @include('panel.sidebar_menu')
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            @include('panel.footer_buttons')
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
        @include('panel.top_navigation')
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main" id="control-panel-contents" ng-view>

          <div class="content-wrapper">

            <div class="text-center mb-xl">
              <p class="lead m0">Crossover Evaluation Assignment 2016</p>
              <div class="text-lg mb-lg">Control Panel</div>
              <p>Loading...</p>
            </div>

          </div>

        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
          <div class="pull-right hidden-xs hidden-sm">
            <i class="fa fa-copyright"></i> 2016 - Sebastián Grignoli | <a href="mailto:grignoli@gmail.com" target="_blank">grignoli@gmail.com</a>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>

@endsection
