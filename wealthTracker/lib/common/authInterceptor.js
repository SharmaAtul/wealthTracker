'use strict';

angular.module('app')
    .factory('authInterceptor', ['$rootScope', '$q', '$window', 'ngToast', function ($rootScope, $q, $window, ngToast) {
    //var pleaseWaitDiv = $(" Please Wait <!– –> Processing );
    return {
        request: function (config) {
            //$rootScope.loading = true;

            $("#request-spinner").show();
            
            config.headers = config.headers || {};
            if ($window.sessionStorage.access_token) {
                config.headers.Authorization = 'Bearer ' + $window.sessionStorage.access_token;
            }
            return config;
        },
        requestError: function (rejection) {
            $("#request-spinner").hide();
            //$rootScope.loading = false;
            //$log.error('Request error:', rejection);
            return $q.reject(rejection);
        },
        response: function (response) {
            $("#request-spinner").hide();
            //$rootScope.loading = false;
            if (response.status === 401) {
                // handle the case where the user is not authenticated
            }
            return response || $q.when(response);
        },
        responseError: function (rejection) {
            if (rejection.statusText == "Bad Request" && rejection.data.message == "UnauthorizedAccess")
            {
                var myToastMsg = ngToast.danger({
                    content: "You are not authorized to view content of this section !"
                });
            }
            $("#request-spinner").hide();
            //$rootScope.loading = false;
            //$log.error('Response error:', rejection);
            return $q.reject(rejection);
        }
    };
}]);