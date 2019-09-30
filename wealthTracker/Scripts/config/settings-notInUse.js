'use strict';

angular.module('app')
    .controller('appController', [
        '$scope','$rootScope',
        function ($scope, $rootScope) {

            //$localStorage.templateUrls = {
            //    searchUserPopup: '../app/areas/common/SearchUserPopup.html',
            //    obsResultPopup: '../app/areas/common/ObsResultPopup.html',
            //    actionItemPopup: '../app/areas/common/ActionItemPopup.html',
            //    attachmentPopup: '../app/areas/common/AttachmentPopup.html',
            //    obsResultObservationPopup: '../app/areas/common/ObsResultObservationPopup.html',
            //    breadcurmb: '../app/areas/common/breadcurmb.html',
            //    observationFilter: '../app/areas/observation/observationFilter.html',
            //};
            
            $scope.name = "Ari";
            $scope.sayHello = function () {
                $scope.greeting = "Hello " + $scope.name;
            }

            $rootScope.globalVar = {
                baseUrl: 'http://localhost:50642/api',
                rootFilter: '',
                showFilter: true,
                userEmail: ''
            };
        }
    ]);
