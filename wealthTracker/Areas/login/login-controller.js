'use strict'

angular.module("app")
    .controller('loginController', ['$scope', '$state', 'loginService', function ($scope, $state, loginService) {
        $scope.loginPageInfo = 'test-login-final';

        $scope.user = {
            grant_type:'password',
            userName: '',
            userPassword: ''
        }

        $scope.loginFormSubmitted = false;

        $scope.authenticateUser = function () {
            $scope.loginFormSubmitted = true;
            if ($scope.loginForm.$valid) {
                loginService.login($scope.user, $scope);
            }
        }

        $scope.registerUser = function () {
            loginService.register($scope.user, $scope);
        }
    }])