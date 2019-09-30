'use strict';

angular.module('app')
    .factory('loginService', ['$http', '$rootScope', '$location', 'sessionService', '$state', 'ngToast', function ($http, $rootScope, $location, sessionService, $state, ngToast) {

        //if (angular.isUndefined($rootScope.globalVar))
        //    $rootScope.globalVar = {};

        var serviceName = "/token";
        $rootScope.stateInfo = {};
        $rootScope.stateParamInfo = {};

        return {
            register: function (userLogin, scope) {
                $http.post("api/users/register", userLogin)
                .success(function (response, status, header, config) {
                    if (response.statusCode == true) {
                        alert('done');
                    }
                })
            },
            refreshSession: function () {
                var authdata = "grant_type=refresh_token&refresh_token=" + sessionStorage.refresh_token + "&client_id=";
                //'grant_type=password&username=' + userLogin.userName + '&password=' + userLogin.userPassword;
                $http({
                    method: 'POST',
                    url: '/token',
                    data: authdata,
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                }).then(function (result) {
                    console.log(result);
                    if (result.status == 200) {

                        sessionStorage.setItem('access_token', result.data.access_token);
                        sessionStorage.setItem('refresh_token', result.data.refresh_token);

                        //$rootScope.globalVar.userEmail = scope.user.username;

                        //if (angular.isUndefined($rootScope.stateInfo.name))
                        //    $state.go('app.clients');
                        //else
                        //    $state.go($rootScope.stateInfo.name, $rootScope.stateParamInfo);
                    }
                    else {
                        console.log(result.statusText);
                    }
                }, function (error) {
                    console.log(error);
                });
            },
            login: function (userLogin, scope) {
                var authdata = 'grant_type=password&username='+userLogin.userName+'&password='+userLogin.userPassword;
                $http({
                    method: 'POST',
                    url: '/token',
                    data: authdata,
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                }).then(function (result) {
                    ngToast.dismiss();
                    if (result.status == 200) {

                        sessionStorage.setItem('access_token', result.data.access_token);
                        sessionStorage.setItem('refresh_token', result.data.refresh_token);
                        sessionStorage.setItem('user_name', userLogin.userName);
                        sessionStorage.setItem('user_role', result.data.user_role);

                        if (sessionStorage.user_role == 'admin')
                            $state.go('app.clients');
                        else {
                            if(window.innerWidth>1200)
                                $state.go('app.dashboard', { "id": result.data.user_id });
                            else
                                $state.go('app.dashboardOther', { "id": result.data.user_id });
                            //$state.go($rootScope.stateInfo.name, $rootScope.stateParamInfo);
                        }
                    }
                }, function (error) {
                    var myToastMsg = ngToast.danger({
                        content: 'Invalid username or password!'
                    });

                });
            },

            getLoggedInUser: function () {

                var user= {
                    "user_role": sessionService.get('user_role'),
                    "user_name": sessionService.get('user_name')
                };
                return user;
            },

            logout: function (toState, toParams) {
                sessionService.destroy('access_token');
                sessionService.destroy('user_role')
                sessionService.destroy('user_name')
                $location.url('login');
            },

            islogged: function () {

                if (sessionService.get('access_token'))
                    return true;
                else
                    return false;
            }
        }

    }]);

