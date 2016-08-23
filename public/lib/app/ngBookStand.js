(function(angular) {
  'use strict';

  angular.module('ngBookStand', [])
    .config(['$interpolateProvider', function($interpolateProvider) {
      $interpolateProvider.startSymbol('{|').endSymbol('|}');
    }])

  // Controllers:

  .controller('BookStandController', ['$scope', '$http', function($scope, $http) {
    $scope.res = {'stand_id_internal': getStandIdInternal(), 'event_id': getEventId(), 'documents':[]};
    top.scope = $scope; //for debugging

    $scope.show_doc_uploader = false;

    $scope.start_doc_uploader = function(){
      $scope.sbup_doc.reset();
      $scope.show_doc_uploader = true;
    };

    $scope.add_document = function(filename){
      $scope.res.documents.push({'filename':filename,'title':''});
      $scope.show_doc_uploader = false;
      $scope.$apply();
    };

    $scope.remove_document = function(item) {
      var index = $scope.res.documents.indexOf(item);
      $scope.res.documents.splice(index, 1);
    }

    $scope.sbup_doc = new SingleBootstrapUploader({
      'element': '#doc',
      'mime': ['application/pdf','image/jpg','image/jpeg','image/png'],
      'success': function(data){ $scope.add_document(data.data.filename);},
      'csrf_token': $('meta[name="csrf-token"]').attr('content')
    });

    $scope.company_logo = function(filename){
      $scope.res.company_logo = filename;
      $scope.$apply();
    };

    $scope.company_logo_reset = function(){
      $scope.res.company_logo = '';
      $scope.sbup_logo.reset();
    };

    $scope.sbup_logo = new SingleBootstrapUploader(
      {
        'element': '#logo',
        // 'cancel': function(){ /* */ },
        'success': function(data){ $scope.company_logo(data.data.filename);},
        'error': function(data){ console.log("ERROR callback called"); console.log(data);},
        'csrf_token': $('meta[name="csrf-token"]').attr('content')
      }
    );

    $scope.make_reservation = function(){
      $http({
        method: 'POST',
        data: {"reservation": $scope.res},
        url: top.APP_PATH+'/API/events/reserve'
      }).then(
         function(response){
             console.log('SUCCESS');
             console.log(response);
             $scope.events = response.data.events;
             //$scope.$apply();
         },
         function(response){
             console.log('ERROR');
             console.log(response);
         });
    }

  }])

;

})(window.angular);

