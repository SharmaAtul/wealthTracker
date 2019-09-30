angular.module("app")
.controller('headerController', ['$scope', '$rootScope', 'loginService', '$interval', 
function ($scope, $rootScope, loginService,$interval) {

    var user = loginService.getLoggedInUser();
    $scope.userEmail = user.user_name;
    $scope.isAdmin = user.user_role == 'admin';

    $scope.logout = function () {
        loginService.logout();
    }
    
    $scope.refreshSession = function () {
        loginService.refreshSession();
    }

    $interval($scope.refreshSession, 3*60*1000);
}])