'use strict';

angular.module('app', [
    'ngAnimate',
    'ui.router',
    'ngSanitize',
    'ngToast',
    'oc.lazyLoad',
    'ncy-angular-breadcrumb',
    'ui.bootstrap',
    'nvd3ChartDirectives',
    'angular-confirm',
    'angularSpinner',
]).run(
        [
            '$rootScope', '$state', '$stateParams', 'loginService', '$location', '$timeout', 
            function ($rootScope, $state, $stateParams, loginService, $location, $timeout) {
                $rootScope.$state = $state;
                $rootScope.$stateParams = $stateParams;
                //'app.home',
                var routeswithpermission = ['app.clients', 'app.dashboard', 'app.clients.fundings', 'app.clients.fundings.detail', 'app.assetAllocation'];  //route that does not require login

                $rootScope.$on('$stateChangeStart', function (e, toState, toParams, fromState, fromParams) {
                    if (routeswithpermission.indexOf(toState.name) >= 0)
                    {
                        //routes that require permissions
                        var statePermissions = toState.permissions.only;
                        var user = loginService.getLoggedInUser();
                        var hasPermission = statePermissions.indexOf(user.user_role) >= 0 || statePermissions.indexOf('*') >= 0;

                        var connected = loginService.islogged();
                        if (!connected || !hasPermission) {
                            $rootScope.stateInfo = toState;
                            $rootScope.stateParamInfo = toParams;
                            loginService.logout();
                        }
                        else {
                            $rootScope.stateInfo = {};
                            $rootScope.stateParamInfo = {};
                        }
                    }
                });
            }
        ]
    ).config(function ($breadcrumbProvider) {
        $breadcrumbProvider.setOptions({
            includeAbstract : true,
        });
    })
.config(['$stateProvider', '$urlRouterProvider', '$locationProvider',
    function ($stateProvider, $urlRouterProvider, $locationProvider) {

        //$locationProvider.html5Mode(true).hashPrefix('!')

        $urlRouterProvider.otherwise('/clients');

        $stateProvider
            .state('login', {
                url: '/login',
                views: {
                    'header@': {
                        template: '',
                    },
                    'content@': {
                        templateUrl: 'areas/login/login.html',
                        controller: 'loginController',
                        resolve: { // Any property in resolve should return a promise and is executed before the view is loaded
                            loadMyCtrl: ['$ocLazyLoad', function ($ocLazyLoad) {
                                // you can lazy load files for an existing module
                                return $ocLazyLoad.load({
                                    serie: true,
                                    name: 'app',
                                    files: [
                                        'areas/login/login-controller.js',
                                        'areas/login/login-service.js'
                                    ]
                                });
                            }]
                        }
                    },
                    'footer@': {
                        template: '',
                    },
                },
                data: {
                    breadcrumbProxy: 'login'
                }
            })
            .state('app', {
                abstract: true,
                url: '/',
                templateUrl: 'lifestyleTracker.html',
                data: {
                    breadcrumbProxy: 'app'
                },
                ncyBreadcrumb: {
                    label: 'app'
                },
            })

        .state('app.clients', {
            url: 'clients',
            permissions: {
                only: ['admin']
            },
            ncyBreadcrumb: {
                label: 'clients'
            },
            views: {
                'header@': {
                    templateUrl: 'appHeaderFooter/header.html',
                    controller: 'headerController',
                    resolve: { // Any property in resolve should return a promise and is executed before the view is loaded
                        loadMyCtrl: ['$ocLazyLoad', function ($ocLazyLoad) {
                            // you can lazy load files for an existing module
                            return $ocLazyLoad.load({
                                serie: true,
                                name: 'app',
                                files: [
                                    'appHeaderFooter/header-controller.js'
                                ]
                            });
                        }]
                    }
                },
                'content@': {
                    templateUrl: 'areas/admin/clients/clients.html',
                    controller: 'clientsController',
                    resolve: { // Any property in resolve should return a promise and is executed before the view is loaded
                        loadMyCtrl: ['$ocLazyLoad', function ($ocLazyLoad) {
                            // you can lazy load files for an existing module
                            return $ocLazyLoad.load({
                                serie: true,
                                name: 'app',
                                files: [
                                    'areas/admin/clients/clients-service.js',
                                    'areas/admin/clients/clients-controller.js'
                                    
                                ]
                            });
                        }]
                    }
                },
                'footer@': {
                    templateUrl: '',
                },
            },
            data: {
                displayName: 'clients',
            }
        })
            .state('app.clients.fundings', {
                url: 'fundings/:id',
                permissions: {
                    only: ['admin']
                },
                //params: { id: null },
                ncyBreadcrumb: {
                    label: 'wealth details'
                },
                views: {
                    'header@': {
                        templateUrl: 'appHeaderFooter/header.html',
                        controller: 'headerController',
                        resolve: { // Any property in resolve should return a promise and is executed before the view is loaded
                            loadMyCtrl: ['$ocLazyLoad', function ($ocLazyLoad) {
                                // you can lazy load files for an existing module
                                return $ocLazyLoad.load({
                                    serie: true,
                                    name: 'app',
                                    files: [
                                        'appHeaderFooter/header-controller.js'
                                    ]
                                });
                            }]
                        }
                    },
                    'content@': {
                        templateUrl: 'areas/admin/clientFunding/clientFunding.html',
                        controller: 'clientFundingController',
                        resolve: { // Any property in resolve should return a promise and is executed before the view is loaded
                            loadMyCtrl: ['$ocLazyLoad', function ($ocLazyLoad) {
                                // you can lazy load files for an existing module
                                return $ocLazyLoad.load({
                                    serie: true,
                                    name: 'app',
                                    files: [
                                        'areas/admin/clientAssetAllocation/clientAssetAllocation-service.js',
                                        'areas/admin/clientFunding/clientFundingController.js',

                                    ]
                                });
                            }]
                        }
                    },
                    'footer@': {
                        templateUrl: '',
                    },
                },
                data: {
                    displayName: 'wealth details',
                }
            })
            .state('app.clients.fundings.detail', {
                url: '/:fundingId',
                permissions: {
                    only: ['admin']
                },
                //params: { id: null },
                ncyBreadcrumb: {
                    label: 'Detail'
                },
                views: {
                    'header@': {
                        templateUrl: 'appHeaderFooter/header.html',
                        controller: 'headerController',
                        resolve: { // Any property in resolve should return a promise and is executed before the view is loaded
                            loadMyCtrl: ['$ocLazyLoad', function ($ocLazyLoad) {
                                // you can lazy load files for an existing module
                                return $ocLazyLoad.load({
                                    serie: true,
                                    name: 'app',
                                    files: [
                                        'appHeaderFooter/header-controller.js'
                                    ]
                                });
                            }]
                        }
                    },
                    'content@': {
                        templateUrl: 'areas/admin/clientAssetAllocation/ClientAssetAllocation.html',
                        controller: 'clientAssetAllocationController',
                        resolve: { // Any property in resolve should return a promise and is executed before the view is loaded
                            loadMyCtrl: ['$ocLazyLoad', function ($ocLazyLoad) {
                                // you can lazy load files for an existing module
                                return $ocLazyLoad.load({
                                    serie: true,
                                    name: 'app',
                                    files: [
                                        'areas/admin/clientAssetAllocation/clientAssetAllocation-service.js',
                                        'areas/admin/assetallocation/assetallocation-service.js',
                                        'areas/admin/clientAssetAllocation/clientAssetAllocation-controller.js',
                                    ]
                                });
                            }]
                        }
                    },
                    'footer@': {
                        templateUrl: '',
                    },
                },
                data: {
                    displayName: 'detail',
                }
            })
            .state('app.dashboard', {
                url: 'dashboard/:id',
                permissions: {
                    only: ['admin','client']
                },
                //params: { id: null },
                ncyBreadcrumb: {
                    label: 'Dashboard'
                },
                views: {
                    'header@': {
                        templateUrl: 'appHeaderFooter/header.html',
                        controller: 'headerController',
                        resolve: { // Any property in resolve should return a promise and is executed before the view is loaded
                            loadMyCtrl: ['$ocLazyLoad', function ($ocLazyLoad) {
                                // you can lazy load files for an existing module
                                return $ocLazyLoad.load({
                                    serie: true,
                                    name: 'app',
                                    files: [
                                        'appHeaderFooter/header-controller.js'
                                    ]
                                });
                            }]
                        }
                    },
                    'content@': {
                        templateUrl: 'areas/dashboard/dashboard.html',
                        controller: 'dashboardController',
                        resolve: { // Any property in resolve should return a promise and is executed before the view is loaded
                            loadMyCtrl: ['$ocLazyLoad', function ($ocLazyLoad) {
                                // you can lazy load files for an existing module
                                return $ocLazyLoad.load({
                                    serie: true,
                                    name: 'app',
                                    files: [
                                        'areas/dashboard/dashboard-service.js',
                                        'areas/dashboard/dashboard-controller.js',
                                    ]
                                });
                            }]
                        }
                    },
                    'footer@': {
                        templateUrl: '',
                    },
                },
                data: {
                    displayName: 'dashboard',
                }
            })
            .state('app.dashboardOther', {
                url: 'dashboardOther/:id',
                permissions: {
                    only: ['admin', 'client']
                },
                //params: { id: null },
                ncyBreadcrumb: {
                    label: 'DashboardOth'
                },
                views: {
                    'header@': {
                        templateUrl: 'appHeaderFooter/headerOther.html',
                        controller: 'headerController',
                        resolve: { // Any property in resolve should return a promise and is executed before the view is loaded
                            loadMyCtrl: ['$ocLazyLoad', function ($ocLazyLoad) {
                                // you can lazy load files for an existing module
                                return $ocLazyLoad.load({
                                    serie: true,
                                    name: 'app',
                                    files: [
                                        'appHeaderFooter/header-controller.js'
                                    ]
                                });
                            }]
                        }
                    },
                    'content@': {
                        templateUrl: 'areas/dashboard/dashboardOther.html',
                        controller: 'dashboardController',
                        resolve: { // Any property in resolve should return a promise and is executed before the view is loaded
                            loadMyCtrl: ['$ocLazyLoad', function ($ocLazyLoad) {
                                // you can lazy load files for an existing module
                                return $ocLazyLoad.load({
                                    serie: true,
                                    name: 'app',
                                    files: [
                                        'areas/dashboard/dashboard-service.js',
                                        'areas/dashboard/dashboard-controller.js',
                                    ]
                                });
                            }]
                        }
                    },
                    'footer@': {
                        templateUrl: '',
                    },
                },
                data: {
                    displayName: 'dashboardOther',
                }
            })
        .state('app.assetAllocation', {
            url: 'assetAllocation',
            permissions: {
                only: ['admin']
            },
            ncyBreadcrumb: {
                label: 'Asset Allocation Settings'
            },
            views: {
                'header@': {
                    templateUrl: 'appHeaderFooter/header.html',
                    controller: 'headerController',
                    resolve: { // Any property in resolve should return a promise and is executed before the view is loaded
                        loadMyCtrl: ['$ocLazyLoad', function ($ocLazyLoad) {
                            // you can lazy load files for an existing module
                            return $ocLazyLoad.load({
                                serie: true,
                                name: 'app',
                                files: [
                                    'appHeaderFooter/header-controller.js'
                                ]
                            });
                        }]
                    }
                },
                'content@': {
                    templateUrl: 'areas/admin/assetallocation/settings.html',
                    controller: 'assetAllocationController',
                    resolve: { // Any property in resolve should return a promise and is executed before the view is loaded
                        loadMyCtrl: ['$ocLazyLoad', function ($ocLazyLoad) {
                            // you can lazy load files for an existing module
                            return $ocLazyLoad.load({
                                serie: true,
                                name: 'app',
                                files: [
                                    'areas/admin/assetallocation/assetallocation-service.js',
                                    'areas/admin/assetallocation/assetallocation-controller.js'
                                ]
                            });
                        }]
                    }
                },
                'footer@': {
                    templateUrl: '',
                },
            },
            data: {
                displayName: 'Asset Allocation Settings',
            }
        })
        
    }]).config(function ($httpProvider) {
        $httpProvider.interceptors.push('authInterceptor');

        //initialize get if not there
        if (!$httpProvider.defaults.headers.get) {
            $httpProvider.defaults.headers.get = {};
        }

        // Answer edited to include suggestions from comments
        // because previous version of code introduced browser-related errors

        //disable IE ajax request caching
        $httpProvider.defaults.headers.get['If-Modified-Since'] = 'Mon, 26 Jul 1997 05:00:00 GMT';
        // extra
        $httpProvider.defaults.headers.get['Cache-Control'] = 'no-cache';
        $httpProvider.defaults.headers.get['Pragma'] = 'no-cache';

    });
    