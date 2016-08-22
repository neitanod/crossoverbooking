(function(angular) {
  'use strict';

  angular.module('ngEvents', ['ngMap'])
    .config(['$interpolateProvider', function($interpolateProvider) {
      $interpolateProvider.startSymbol('{|').endSymbol('|}');
    }])

  // Controllers:

  .controller('EventsController', ['$scope', 'NgMap', '$http', function($scope, NgMap, $http) {
    $scope.events = {};

    var vm = this;
    NgMap.getMap().then(function(map) {
      // Grab the map so I can bind the clicks to my custom callbacks
      vm.map = map;
    });

    vm.showEvent = function(ev,id){
      $scope.selectEvent(id);
      scrollToElement("#call-to-action");
    };

    $scope.selectEvent = function(id){
      $('#event-details').hide();
      $scope.ev = $scope.events[id];
      $scope.$apply();
      $('#event-details').fadeIn();
    };

    $scope.init = function() {
      $http({
        method: 'GET',
        data: {},
        url: top.APP_PATH+'/API/events/list'
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
    $scope.init();
  }])

  .filter('fullDate', function(){
    return function (date){
      return moment(date).format('ddd, MMMM Do, YYYY');
    }
  })

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
  });

;

})(window.angular);
