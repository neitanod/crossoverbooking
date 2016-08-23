describe('ngEvents', function() {
    beforeEach(module('ngEvents'));

    var $controller;

    beforeEach(inject(function(_$controller_){
        // The injector unwraps the underscores (_) from around the parameter names when matching
        console.log('INJECTING!!');
        $controller = _$controller_;
    }));

    describe('$scope.selectEvent', function() {
        it('It should ', function() {


            var $scope = {
                $apply: function() {
                    console.log('called');
                }
            };
            spyOn($scope, '$apply')
            spyOn($.fn, 'hide')

            var controller = $controller('EventsController', { $scope: $scope });
            var centinel = 'XXXXXXXXXX';
            var idToTest = 25;
            $scope.events[idToTest] = centinel;
            $scope.selectEvent(idToTest);

            expect($scope.$apply.calls.length).toEqual(1);
            expect($.fn.hide.calls.length).toEqual(1);
            expect($scope.ev).toEqual(centinel)

        });
    });
});
