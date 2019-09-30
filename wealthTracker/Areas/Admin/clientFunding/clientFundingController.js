angular.module('app')
.controller('clientFundingController', ['$scope', '$state', '$stateParams', 'clientAssetAllocationService', function ($scope, $state, $stateParams, clientAssetAllocationService)
{
    $scope.totalItems = 0;
    $scope.currentPage = 1;
    $scope.pageSize = 20;
    $scope.userEmail = '';

    $scope.clientFundings = [];

    $scope.getClientFundings = function () {
        var data = {
            clientID: $stateParams.id,
            currentPage: $scope.currentPage-1,
            pageSize: $scope.pageSize
        }
        clientAssetAllocationService.getClientFundings(data)
            .then(
                    function (data) {

                        $scope.clientFundings = data.records;
                        $scope.userDetail = data.userDetail;
                        $scope.totalItems = data.totalCount;
                    }
                );
    }

    $scope.deleteFunding = function (index) {
        var client = $scope.clientFundings[index];
        clientAssetAllocationService.deleteClientFundings(client.clientFundingID)
            .then(
                    function (data) {

                        $scope.clientFundings.splice(index, 1);
                    }
                );
    }

    $scope.editFunding = function (index) {
        var fundingId= $scope.clientFundings[index].clientFundingID;
        $state.go('app.clients.fundings.detail', { "id": $stateParams.id, "fundingId": fundingId })
    }

    $scope.addFunding = function () {
        $state.go('app.clients.fundings.detail', { "id": $stateParams.id, "fundingId": 0 })
    }

    $scope.viewDashboard = function () {
        var userId = $stateParams.id;
        $state.go('app.dashboard', { "id": userId });
    }

    

    $scope.cancel = function () {
        $state.go('app.clients');
    }

    $scope.getClientFundings();

    $scope.setPage = function (pageNo) {
        $scope.currentPage = pageNo;
    };

    $scope.pageChanged = function () {
        $scope.getClientFundings();
        //$log.log('Page changed to: ' + $scope.currentPage);
    };

    $scope.pagesToDisplay = 5;
}])