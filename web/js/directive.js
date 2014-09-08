var lifeListApp = angular.module('lifeListApp');

lifeListApp.directive('focusMe', function($timeout) {
    return {
        scope: {trigger: '=focusMe'},
        link: function(scope, element) {
            scope.$watch('trigger', function(value) {
                if (value === true) {
                    $timeout(function() {
                        element[0].focus();
                    }, 0);
                }
            });
        }
    };
});
lifeListApp.directive('pjax', function($http) {
    return {
        link: function(scope, element, attrs) {
            //$(document).pjax(element, '#pjax-container');
        }
    };
});