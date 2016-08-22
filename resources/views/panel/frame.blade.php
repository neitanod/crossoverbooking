<!DOCTYPE html>
<html manifest__xxdisabled_attribute="cache.manifest" data-lang="es">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">

    <title id="html-title"></title>

    <style>
      /* Necesarios para el funcionamiento del preloader: */
      .wrapper{
        display: block;
      }
      .loading>.wrapper{
        display: none;
      }
      .preload-wrapper{
        display: none;
      }
      .loading>.preload-wrapper{
        display: block;
      }

      /* Opcionales para el diseño: */
      body.loading{
        background-color: #2a3f54; /*#23B7E5;*/
      }
      .preload-wrapper{
        padding-top: 40px;
        text-align: center;
        color: white;
        font-family: Verdana, Arial, Helvética;
        font-size: 14px;
        line-height: 2;
      }
      .preload-wrapper .preload-centered{
        display: inline-block;
      }
      .preload-wrapper .wait-animation {
        margin-top: 20px;
        width: 40px;
        height: 40px;
      }
    </style>
  </head>
  <body class="loading nav-md" ng-app="ngControlPanel" ng-strict-di>
    <div class="preload-wrapper">
      <div class="brand-logo preload-centered">
        <img src="{{ asset('images/logo-panel-wide.png') }}" alt="App Logo" class="img-responsive">
      </div>
      <div>Loading...</div>
      <img class="wait-animation" src="{{ asset('images/loading_white_on_transparent.gif') }}">
    </div>
    <div class="wrapper container body">
    @yield('menu')
    @yield('body')
    </div>
  </body>
  <script>
    {{--
    Here we define a JS variable in order to help static JS and HTML
    files to know the root path of the app without having to be rendered
    by Laravel
    --}}
    top.APP_PATH="{{ URL::to('/') }}";
  </script>
  <script src="{{ asset('lib/rsvp-3.0.8/rsvp.min.js') }}"></script>
  <script src="{{ asset('lib/basket-0.5.2.patched/basket.js') }}"></script>
  <script>
    var stylesheets = [
      {url: "{{ asset('lib/bootstrap-3.3.6/css/bootstrap.min.css') }}"},
      {url: "{{ asset('lib/font-awesome-4.5.0/css/font-awesome.min.css') }}"},
      {url: "{{ asset('lib/gentelella/custom.min.css') }}"},
      // {url: "{{ asset('lib/iCheck/skins/flat/green.css') }}"},
      {url: "{{ asset('lib/pnotify/pnotify.custom.min.css') }}"},
      // {url: "{{ asset('lib/alertify.js-0.3.11.patched/themes/alertify.core.css') }}"},
      // {url: "{{ asset('lib/alertify.js-0.3.11.patched/themes/alertify.default.css') }}"},
      @if(!(config('app.env') == 'dev'))
        {url: "{{ asset('panel_files/panel.css') }}"}
      @else
        {url: "{{ asset('panel_files/panel.css') }}", skipCache: true}  // no uso cache mientras estoy desarrollando
      @endif
    ];
    basket.preload( stylesheets ).then(function(){
    basket.css( stylesheets ).then(function(){
        $('#panel-body').show();
      });
    });

    var javascripts = [
      {url: "{{ asset('lib/jquery-1.11.3/jquery-1.11.3.min.js') }}"},
      {url: "{{ asset('lib/bootstrap-3.3.6/js/bootstrap.min.js') }}"},
      {url: "{{ asset('lib/app/utils.js') }}"},
      // ================ GENTELELLA ====================
      {url: "{{ asset('lib/modernizr-3.2.0-custom/modernizr.custom.js') }}"},
      {url: "{{ asset('lib/gentelella/custom.js') }}"},
      // =============== PAGE VENDOR SCRIPTS ===============
      // FASTCLICK
      {url: "{{ asset('lib/fastclick/lib/fastclick.js') }}"},
      // PNOTIFY
      {url: "{{ asset('lib/pnotify/pnotify.custom.min.js') }}"},
      // SCREENFULL
      {url: "{{ asset('lib/screenfull-3.0.0/screenfull.min.js') }}"},


      // ========= Panel =============
      {url: "{{ asset('lib/angular-1.5.0/angular.min.js') }}"},
      {url: "{{ asset('lib/angular-1.5.0/angular-route.min.js') }}"},

      @if(!(config('app.env') == 'dev'))
        {url: "{{ asset('panel_files/Panel.js') }}"},
        {url: "{{ asset('panel_files/ngControlPanel.js') }}"}
      @else
        // do not use cache while developing
        {url: "{{ asset('panel_files/Panel.js') }}", skipCache: true},
        {url: "{{ asset('panel_files/ngControlPanel.js') }}", skipCache: true}
      @endif
      // ========= /Panel =============

    ];

    basket.preload( javascripts ).then(function(){
      basket.execute( javascripts ).then(function(){
        panel = new Panel();
        panel.initialize();
        $('body').removeClass("loading");
      });
    });

  </script>
</html>
