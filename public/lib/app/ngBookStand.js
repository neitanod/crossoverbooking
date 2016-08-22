(function(angular) {
  'use strict';

  angular.module('ngBookStand', [])
    .config(['$interpolateProvider', function($interpolateProvider) {
      $interpolateProvider.startSymbol('{|').endSymbol('|}');
    }])

  // Controllers:

  .controller('BookStandController', ['$scope', '$http', function($scope, $http) {

  }])

;

})(window.angular);

