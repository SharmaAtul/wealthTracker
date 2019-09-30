angular.module("app")
    .controller("dashboardController", ['$scope', '$state', '$stateParams', 'dashboardService', 'loginService', '$window',
        function ($scope, $state, $stateParams, dashboardService, loginService, $window) {

        var user = loginService.getLoggedInUser();
        $scope.isAdmin = user.user_role == 'admin';

        $scope.assetClassesReportData = [];
        $scope.netWealthReportData = [];

        $scope.assetClassesComparisonReportData = [];
        $scope.netWealthComparisonReportData = [];

        $scope.userDetail = {};
        $scope.lastFunding = {};
        $scope.yaxistickformat = function () {
            return function (d) {
                return '$'+d3.format(',.0d')(d);
            }
        }
        $scope.yaxistickformatclass = function () {
            return function (d) {
                return d3.format(',.f')(d) + '%';
            }
        }

        $scope.margin = {
            left:10
        }

        //$scope.init();

        $scope.expectedTotalFund = 0;
        $scope.actualTotalFund = 0;

        $scope.expectedGrowth = 0;
        $scope.actualGrowth = 0;

        $scope.loadDashboardData = function () {
            dashboardService.getDashboardData($stateParams.id)
            .then(
                function (response) {
                    $scope.assetClassesReportData = response.assetClassesReportData;
                    $scope.netWealthReportData = response.netWealthReportData;
                    $scope.assetClassesComparisonReportData = response.assetClassesComparisonReportData;
                    $scope.assetClassesComparisonReportData[0].area = true;
                    $scope.assetClassesComparisonReportData[1].area = true;

                    $scope.netWealthComparisonReportData = response.netWealthComparisonReportData;
                    $scope.netWealthComparisonReportData[0].area = true;
                    $scope.netWealthComparisonReportData[1].area = true;

                    $scope.userDetail = response.userData;
                    $scope.lastFunding = response.lastFunding;

                    $scope.expectedTotalFund = response.expectedTotalFund
                    $scope.actualTotalFund = $scope.lastFunding.totalAssets;

                    $scope.expectedGrowth = $scope.lastFunding.expectedAusShares + $scope.lastFunding.expectedIntShares + $scope.lastFunding.expectedProperty
                            + $scope.lastFunding.expectedAttractiveAssets;

                    $scope.actualGrowth = $scope.lastFunding.actualAusShares + $scope.lastFunding.actualIntShares + $scope.lastFunding.actualProperty
                            + $scope.lastFunding.actualAttractiveAssets;
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

        $scope.cancel = function () { $state.go('app.clients') }

        $scope.options = {
            chart: {
                type: 'multiChart',
                height: 450,
                margin: {
                    top: 30,
                    right: 60,
                    bottom: 50,
                    left: 70
                },
                //color: d3.scale.category10().range(),
                //useInteractiveGuideline: true,
                duration: 500,
                xAxis: {
                    tickFormat: function (d) {
                        return d3.format(',f')(d);
                    }
                },
                yAxis1: {
                    tickFormat: function (d) {
                        return d3.format(',d')(d);
                    }
                }
            }
        };

        

        function generateData() {
            var testdata = [
              { color: 'green', key: 'test1', area:true, values: [[0, 2], [1, 15], [2, 18]] },
              { color: 'red', key: 'test2', area: true, values: [[0, 6], [1, 9], [2, 15]] },
            ]

            return testdata;
        }

        $scope.lineChartData = generateData();

}]);