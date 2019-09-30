angular.module("app")
    .controller("dashboardController", ['$scope', '$state', '$stateParams', 'dashboardService', 'loginService', function ($scope, $state, $stateParams, dashboardService, loginService) {

        var user = loginService.getLoggedInUser();
        $scope.isAdmin = user.user_role == 'admin';

        $scope.reportData = [];
        $scope.reportData2 = [];
        $scope.userDetail = {};
        $scope.lastFunding = {};
        $scope.yaxistickformat = function () {
            return function (d) {
                return d3.format(',.0d')(d);
            }
        }

        $scope.margin = {
            left:10
        }

        $scope.loadDashboardData = function () {
            dashboardService.getDashboardData($stateParams.id)
            .then(
                function (response) {
                    $scope.reportData = response.listData;
                    $scope.reportData2 = response.listData2;
                    $scope.userDetail = response.userData;
                    $scope.lastFunding = response.lastFunding
                }
            );
        }

        $scope.loadDashboardData();

        $scope.toolTipContentFunction = function () {
            return function (key, x, y, e, graph) {
                return'<h3>' + key + '</h3>' +
                    '<p>' + y + ' at ' + x + '</p>'
            }
        }

        $scope.cancel = function () { $state.go('app.clients')}
}]);