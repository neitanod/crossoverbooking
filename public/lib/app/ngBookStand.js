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

    $scope.add_document = function(filename){
      $scope.res.documents.push({'filename':filename,'title':''});
      $scope.show_doc_uploader = false;
      $scope.$apply();
    };

    $scope.sbup_doc = new SingleBootstrapUploader({
      'element': '#doc',
      'mime': ['application/pdf','image/jpg','image/jpeg','image/png'],
      'success': function(data){ $scope.add_document(data.data.filename);},
      'csrf_token': $('meta[name="csrf-token"]').attr('content')
    });

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

