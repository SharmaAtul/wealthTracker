'use strict';

angular.module('app')
    .controller('AppCtrl', [
        '$rootScope', '$state', '$timeout','loginService', 
function ($rootScope, $state, $timeout, loginService) {

            $rootScope.templateUrls = {
                searchUserPopup: '../app/areas/common/SearchUserPopup.html',
                obsResultPopup: '../app/areas/common/ObsResultPopup.html',
                actionItemPopup: '../app/areas/common/ActionItemPopup.html',
                attachmentPopup: '../app/areas/common/AttachmentPopup.html',
                obsResultObservationPopup: '../app/areas/common/ObsResultObservationPopup.html',
                breadcurmb: '../app/areas/common/breadcurmb.html',
                observationFilter: '../app/areas/observation/observationFilter.html',
            };

            $rootScope.globalVar = {
                //baseUrl: 'http://apps.exp-inc.com/EXPiOSWebservicesTest/ServiceIOS.svc',
                baseUrl: 'http://localhost:26996/api/',
                rootFilter: '',
                showFilter: true,
                userEmail:''
            };

           
            //if (angular.isDefined($localStorage.settings))
            //    $rootScope.settings = $localStorage.settings;
            //else
            //    $localStorage.settings = $rootScope.settings;

            //$rootScope.$watch('settings', function () {
            //    if ($rootScope.settings.fixed.header) {
            //        $rootScope.settings.fixed.navbar = true;
            //        $rootScope.settings.fixed.sidebar = true;
            //        $rootScope.settings.fixed.breadcrumbs = true;
            //    }
            //    if ($rootScope.settings.fixed.breadcrumbs) {
            //        $rootScope.settings.fixed.navbar = true;
            //        $rootScope.settings.fixed.sidebar = true;
            //    }
            //    if ($rootScope.settings.fixed.sidebar) {
            //        $rootScope.settings.fixed.navbar = true;


            //        //Slim Scrolling for Sidebar Menu in fix state
            //        var position = $rootScope.settings.rtl ? 'right' : 'left';
            //        if (!$('.page-sidebar').hasClass('menu-compact')) {
            //            $('.sidebar-menu').slimscroll({
            //                position: position,
            //                size: '3px',
            //                color: $rootScope.settings.color.themeprimary,
            //                height: $(window).height() - 90,
            //            });
            //        }
            //    } else {
            //        if ($(".sidebar-menu").closest("div").hasClass("slimScrollDiv")) {
            //            $(".sidebar-menu").slimScroll({ destroy: true });
            //            $(".sidebar-menu").attr('style', '');
            //        }
            //    }

            //    $localStorage.settings = $rootScope.settings;
            //}, true);

            //Logout
            $rootScope.logout = function () {
                loginService.logout();
            }

        }
    ]);