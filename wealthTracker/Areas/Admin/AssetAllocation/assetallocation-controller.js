angular.module('app')
    .controller('assetAllocationController', ['$scope', 'assetAllocationService','ngToast', function ($scope, assetAllocationService, ngToast) {

        $scope.riskProfileData = {};

        $scope.getAssestAllocation = function () {
            assetAllocationService.getAssetAllocation()
                .then(
                        function (data) {
                            $scope.riskProfileData = data.sort(function (a, b) {
                                return a.applySort - b.applySort;
                                    }
                                );
                            for (var i = 0; i <= $scope.riskProfileData.length - 1; i++) {
                                $scope.updateTotal(i);
                            }
                        }
                    );
        }

        $scope.movedown = function (index) {

            var newList = $scope.riskProfileData;

            var newSortOrder = newList[index + 1].applySort;
            newList[index + 1].applySort = newList[index].applySort;
            newList[index].applySort = newSortOrder;

            $scope.riskProfileData = newList.sort(function (a, b) {
                                        return a.applySort - b.applySort;
                                    }
                                );
            
        }

        $scope.moveup = function (index) {

            var newList = $scope.riskProfileData;

            var newSortOrder = newList[index - 1].applySort;
            newList[index - 1].applySort = newList[index].applySort;
            newList[index].applySort = newSortOrder;

            $scope.riskProfileData = newList.sort(function (a, b) {
                                        return a.applySort - b.applySort;
                                    }
                                );
        }

        $scope.getAssestAllocation();
        $scope.errorMessage = "";

        $scope.updateAllocationSetttings = function () {
            //$scope.errorMessage = "";
            for (var i = 0; i <= $scope.riskProfileData.length - 1; i++)
            {
                if ($scope.riskProfileData[i].total != 100)
                {
                    $scope.errorMessage = "Data for '" + $scope.riskProfileData[i].riskProfileType + "' is not valid !";
                    return;
                }
            }

            assetAllocationService.updateAssetAllocation($scope.riskProfileData)
                .then(
                        function (data) {
                            $scope.errorMessage = "";
                            var myToastMsg = ngToast.success({
                                content: "Data updated successfully!"
                            });
                        }
                    );
        }

        $scope.updateTotal = function(index)
        {
            var riskData = $scope.riskProfileData[index];

            riskData.total = riskData.ausShares + riskData.intShares + riskData.property + riskData.ausFixedInterest + riskData.intFixedInterest + riskData.cash + riskData.attractiveAssets;
        }
        
        $scope.operation = "";
        $scope.riskProfileFormSubmitted = false;

        $scope.updateRiskProfileTitle = function () {
            if ($scope.riskProfileTypeInEdit == $scope.newRiskProfileType) {
                $scope.errorMessage = "New Name and Old Name for Risk profile can't be same!";
                return;
            }

            $scope.riskProfileFormSubmitted = true;

            if ($scope.riskProfileForm.$valid || $scope.operation == "delete") {
                //$scope.errorMessage = "";
                var data =
                    {
                        oldName: $scope.riskProfileTypeInEdit,
                        newName: $scope.newRiskProfileType
                    }

                assetAllocationService.updateRiskProfileTitle(data)
                    .then(
                            function (data) {
                                if (data == "IN_USE") {
                                    var myToastMsg = ngToast.danger({
                                        content: "Risk profile type can not be deleted, it is in use!"
                                    });
                                    return;

                                } else if (data == "ALREADY_EXISTS") {
                                    var myToastMsg = ngToast.danger({
                                        content: "Risk profile type already exists!"
                                    });

                                    $scope.errorMessage = "Risk profile type already exists!"
                                    return;
                                }

                                if ($scope.operation == "edit") {
                                    $('#btnCancel').click();

                                    var myToastMsg = ngToast.success({
                                        content: "Risk profile type updated successfully!"
                                    });
                                }
                                else if ($scope.operation == "add") {
                                    $('#btnCancel').click();

                                    var myToastMsg = ngToast.success({
                                        content: "Risk profile type added successfully!"
                                    });
                                }
                                else if ($scope.operation == "delete") {
                                    var myToastMsg = ngToast.success({
                                        content: "Risk profile type deleted successfully!"
                                    });
                                }

                                $scope.riskProfileData = data;
                            }
                        );
            }
        }

        $scope.deleteRiskProfile = function (index) {
            $scope.riskProfileTypeInEdit = $scope.riskProfileData[index].displayField;
            $scope.newRiskProfileType = "";
            $scope.operation = "delete";
            $scope.updateRiskProfileTitle();
        }
        
        $scope.editRiskProfile = function (index) {

            $scope.errorMessage = "";
            $scope.riskProfileFormSubmitted = false;
            $scope.riskProfileTypeTitle = "Edit Risk profile type";
            $scope.riskProfileTypeInEdit = $scope.riskProfileData[index].displayField;
            $scope.newRiskProfileType = "";
            $scope.isRiskProfileEdit = true;
            $scope.operation = "edit";
            $('#aRiskProfileTypeForm').click();
        }

        $scope.addRiskProfile = function (index) {
            $scope.errorMessage = "";
            $scope.riskProfileFormSubmitted = false;
            $scope.riskProfileTypeTitle = "Add New Risk profile type";
            $scope.newRiskProfileType = "";
            $scope.riskProfileTypeInEdit = "";
            $scope.isRiskProfileEdit = false;
            $scope.operation = "add";
            $('#aRiskProfileTypeForm').click();
        }
}])