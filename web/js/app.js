//PJAX
$(document).ready(function()
{
    $(document).pjax('a[data-pjax]', '#pjax-container');
});

//Angular
var lifeListApp = angular
        .module('lifeListApp', ['ui.bootstrap', 'ngResource', 'deferreddata'])
        .config(['$interpolateProvider', function($interpolateProvider) {
                $interpolateProvider.startSymbol('[[');
                $interpolateProvider.endSymbol(']]');
            }])
        .run(function($compile, $rootScope, $document) {
            $document.on('pjax:success', function() {
                var pjax = angular.element('#pjax-container'),
                        compiled = $compile(pjax.html())($rootScope);

                pjax.html(compiled);
            });
            $document.on('pjax:beforeSend', function(event, xhr, options)
            {
                options.url = stripPjaxParam(options.url);
            });
        });


function stripPjaxParam(url) {
    return url
            .replace(/\?_pjax=[^&]+&?/, '?')
            .replace(/_pjax=[^&]+&?/, '')
            .replace(/[\?&]$/, '');
}