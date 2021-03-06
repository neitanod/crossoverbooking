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

    $scope.errors = {};

    $scope.revalidate = false;

    $scope.validate = function(){
      $scope.revalidate = true;
      $scope.errors.company_logo = !$scope.res.company_logo;
      $scope.errors.company_name = !$scope.res.company_name;
      $scope.errors.admin_name   = !$scope.res.admin_name;
      $scope.errors.admin_email  = !validEmail($scope.res.admin_email);
      $scope.errors.phone  = !$scope.res.phone;
      $scope.errors.email  = !validEmail($scope.res.email);
      $scope.errors.website  = !$scope.res.website;
      $scope.errors.facebook  = !$scope.res.facebook;
      $scope.errors.twitter  = !$scope.res.twitter;
      //$scope.errors.documents  = !$scope.res.documents;
      return !(
      $scope.errors.company_logo ||
      $scope.errors.company_name ||
      $scope.errors.admin_name  ||
      $scope.errors.admin_email ||
      $scope.errors.phone  ||
      $scope.errors.email );
    }

    $scope.make_reservation = function(){
      if(!$scope.validate()){
        BootstrapDialog.alert('Please complete all required fields');
        return;
      }
      $http({
        method: 'POST',
        data: {"reservation": $scope.res},
        url: top.APP_PATH+'/API/event/reserve'
      }).then(
         function(response){
             console.log('SUCCESS');
             console.log(response);
             top.location.href = getAppPath()+"/event/"+getEventId()+"#"+getStandIdInternal();
         },
         function(response){
             top.location.href=top.APP_PATH+'/'+response.status;
         });
    }

  }])

;

})(window.angular);

