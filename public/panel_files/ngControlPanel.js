(function(angular) {
  'use strict';

  angular.module('ngControlPanel', ['ngRoute'])
    .config(['$interpolateProvider', '$routeProvider', function($interpolateProvider, $routeProvider) {

      $routeProvider.
      when('/dashboard', {
        templateUrl: top.APP_PATH+'/panel_files/views/dashboard.html',
        controller: 'DashboardController'
      }).
      when('/users', {
        templateUrl: top.APP_PATH+'/panel_files/views/users.html',
        controller: 'UsersController'
      }).
      otherwise({
        redirectTo: top.APP_PATH+'/dashboard'
      });

      $interpolateProvider.startSymbol('{|').endSymbol('|}');
    }])



  // Controllers:

  .controller('StatusController', ['$scope', function($scope) {
    $scope.messages = [{},{}];
    $scope.unread = 0;
    $scope.init = function() {
      query('users',{},
          function(returned_data){
            console.log("returned_data:");
            console.log(returned_data);
            $scope.users = returned_data.users;
            $scope.$apply();
            enable_section();
            done();
          });
      ;
    }
    $scope.init = function() {
    };

    $scope.init();
  }])

  .controller('DashboardController', ['$scope', function($scope) {
  }])


  .controller('UsersController', ['$scope', function($scope) {
    $scope.users = [];

    $scope.init = function() {
      disable_section();
      var done = alert(h4(wait("Cargando..."))).later();

      query('users',{},
          function(returned_data){
            console.log("returned_data:");
            console.log(returned_data);
            $scope.users = returned_data.users;
            $scope.$apply();
            enable_section();
            done();
          });
    };
    $scope.init();
  }])

  .filter('toDate', function () {
    return function (date) {
      return new Date(date);
    }
  })

  .filter('search', function () {
    return function (items, search) {
      if(search === "") return items;
      search=search.toLowerCase();
      var result = {};
      angular.forEach(items, function (item, id) {
        angular.forEach(item, function (value, key) {
          console.log(value, typeof value == "string"?value.indexOf(search):"" );
          if (typeof value == "string" && (value.toLowerCase().indexOf(search) !== -1)) {
            result[id] = item;
          }
        })
      });
      return result;

    }
  })

;

})(window.angular);

(function($body, counter){
    $body.on('dragenter',  function(){$body.addClass("draghover"); counter++;})
         .on('drop',       function(){$body.removeClass("draghover"); counter=0;})
         .on('dragleave', function(){counter--; if(!counter){$body.removeClass("draghover");}});
})($('body'), 0);

// pide status cada 30 segundos y si se deslogueó avisa.
//setInterval(retrieveNotifications,30000);
setInterval(retrieveNotifications,3000);

function processNotifications(data){
  console.group('ProcessNotifications');
  console.log(data);
  console.groupEnd();
  checkVersion(data["app_version"]);
}

function retrieveNotifications(){
  query("status",{},processNotifications);
}

retrieveNotifications();

function checkVersion(version){
  if(!version) return;
  var current_version = localStorage.getItem("app_version");
  console.group("Check Version");
  console.log("ACTIVE VERSION:", current_version);
  console.log("NEW VERSION:", version);
  console.groupEnd();
  if(!top.reloadlater && current_version == "latest" || current_version == "" || current_version == null){
    localStorage.setItem("app_version", version);
  } else {
    if(version != current_version){
      //alert(h4(wait(" ")+" Actualizando... "), "Nueva versión de la aplicación disponible.");
      console.log("top.reloadlater: ",top.reloadlater);
      //top.applicationCache.update();
      localStorage.clear();
      localStorage.setItem("app_version", version);
      if(!top.reloadlater) {
        ask_new_panel_version(
          // Saving before exiting
          function(ok){
            if(ok) {
              top.location.reload();
            } else {
              top.reloadlater = true;
            }
          }
        );
      }
    }
  }
}

function query(queryname, data, callback){
  var delay = top.fake_network_delay?parseFloat(top.fake_network_delay):0;
  var packet = {};
  if( data == undefined ) data = {};
  packet.query = queryname||"status";
  packet.data = angular.toJson(data);
  $.ajax({
    url: "/API/panel",   // Entry point de la API del Panel.
    data: packet,
    method: 'get',
    context: document.body,
    success: function(response, status, r){
      if(typeof(response)=='string'){response = JSON.parse(response);}
      console.log(typeof(response));
      returned_data = response["data"];
      console.log("> Success!  Status: "+status);
      console.log(r.responseText.substr(0,100)+"...");
      console.log("Packet:", packet);
      console.log("Response:", response);
      console.log("Request:", r);
      console.log("returned_data: ",response['data']);
      if(response["code"]==401){
        alertError(h4("La sesión ha expirado. Vuelva a ingresar."),undefined,function(){top.location.href="/panel/e/"+getEstablishmentSlug()+"?nocache";});
      } else if(response["code"]!=200){
        console.group("ERROR");
        console.log("xResponse:");
        console.log(response);
        console.log("Error code:",response['code']);
        console.groupEnd();
        //alertError(h4(response["message"]));
      } else {
        console.group("OK");
        console.log(returned_data);
        console.groupEnd();
        if(callback) setTimeout(function(){callback(returned_data);}, delay);
      }
    },
    complete: function(r, status){
      console.log("> Completed with status: "+status+" "+r.status+" "+r.statusText);
      console.log(r.responseText.substr(0,100)+"...");
      console.log("Request:", r);
    },
    error: function(r, status){
      console.log("> Error: "+status+" "+r.status+" "+r.statusText);
      console.log(r.responseText.substr(0,100)+"...");
      console.log("Request:", r);
    }
  });
}

function disable_section() {
  $('.ngview-contents').addClass('disabled');
}

function enable_section() {
  $('.ngview-contents').removeClass('disabled');
}

function ask_new_panel_version(cb) {
  if(!top.reloadasking){
    top.reloadasking = true;
    alertify.set({ labels: {
      ok     : "Reload now",
      cancel : "Reload later"
    } });
    alertify.confirm("A newer version of the application is available.", cb);
  }
}

function ask_save(cb) {
  alertify.set({ labels: {
    ok     : "Save",
    cancel : "Discard changes"
  } });
  alertify.confirm("¿Save changes?", cb);
}

function confirm_save(msg, cb) {
  alertify.set({ labels: {
    ok     : "Save",
    cancel : "Cancel"
  } });
  alertify.confirm(msg, cb);
}

function expired(cb) {
  alertify.confirm("Session has expired.", cb);
}

function initialize_uploader(all_done_callback){
  // jQuery File Upload Plugin
  // https://github.com/blueimp/jQuery-File-Upload

  'use strict';

  var all_done = function(){
    $('#fileupload').find(".files").empty();
    if(typeof all_done_callback == "function") all_done_callback();
  }

  console.log("Initializing UPLOADER");

  var upload_count = 0;

  $('.fileupload').each(function () {
    $(this).fileupload({
      dropZone: $(this).find(".fileupload-dropzone"),
      autoUpload: true
    })
    .bind('fileuploadadd', function (e, data) {upload_count++;})
    .bind('fileuploaddone', function (e, data) {upload_count--; if(!upload_count) {all_done();}})
    ;//.addClass('fileupload-processing');

  });
  console.log("UPLOADER Initialized");

}
