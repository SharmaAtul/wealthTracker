angular.module('app')
    .directive('fileModel', ['$parse', function ($parse) {
        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                var model = $parse(attrs.fileModel);
                var modelSetter = model.assign;

                element.bind('change', function () {
                    scope.$apply(function () {
                        modelSetter(scope, element[0].files[0]);
                    });
                });
            }
        };
    }]);



angular.module('app')
    .controller('clientAssetAllocationController', ['$scope', '$state', '$stateParams', 'clientAssetAllocationService', 'assetAllocationService', '$filter', 
function ($scope, $state, $stateParams, clientAssetAllocationService, assetAllocationService, $filter) {
    $scope.clientData = {};

    $scope.riskProfileTypes = {
        data: [
                { riskProfileType: "Aggressive", displayField: "AA" },
                { riskProfileType: "Balanced", displayField: "BB" },
                { riskProfileType: "Conservative", displayField: "BB" },
                { riskProfileType: "Growth", displayField: "BB" },
                { riskProfileType: "Moderate", displayField: "BB" },
                { riskProfileType: "B", displayField: "BB" },
                { riskProfileType: "B", displayField: "BB" }
        ]
    }


    $scope.projectedData = []
    $scope.isAddNew = false;
    $scope.expectedTotalFund = 0;
    $scope.actualTotalFund = 0;

    $scope.expectedGrowth = 0;
    $scope.actualGrowth = 0;

    $scope.fundingYear = 0;

    $scope.getClientFunding = function () {
        clientAssetAllocationService.getClientFundingById($stateParams.id, $stateParams.fundingId)
        .then(
                function (response) {
                    $scope.clientData = response;
                    $scope.clientData.funding.userEmail = $scope.clientData.user.userEmail;
                    $scope.riskProfileTypes.data = $scope.clientData.riskProfiles;
                    $scope.projectedData = $scope.clientData.listProjectedData;
                    
                    $scope.expectedTotalFund = $scope.clientData.expectedTotalFund;

                    $scope.actualTotalFund = $scope.clientData.funding.totalAssets;

                    if ($scope.clientData.funding.clientFundingID == 0)
                        $scope.isAddNew = true;

                    $scope.expectedGrowth = $scope.clientData.funding.expectedAusShares + $scope.clientData.funding.expectedIntShares + $scope.clientData.funding.expectedProperty
                            + $scope.clientData.funding.expectedAttractiveAssets;

                    $scope.actualGrowth = $scope.clientData.funding.actualAusShares + $scope.clientData.funding.actualIntShares + $scope.clientData.funding.actualProperty
                            + $scope.clientData.funding.actualAttractiveAssets;

                    $scope.clientData.funding.totalShares = $scope.clientData.funding.actualAusShares + $scope.clientData.funding.actualIntShares + $scope.clientData.funding.actualProperty
                            + $scope.clientData.funding.actualAusFixedInterest + $scope.clientData.funding.actualIntFixedInterest + $scope.clientData.funding.actualCash
                            + $scope.clientData.funding.actualAttractiveAssets;
                }
            );
    }

    $scope.loadRiskProfileValues = function () {
        assetAllocationService.getAssetAllocationByKey($scope.clientData.funding.riskProfile)
        .then(
                function (response) {
                    console.log(response);
                    $scope.clientData.funding.expectedAusShares = response.ausShares;
                    $scope.clientData.funding.expectedIntShares = response.intShares;
                    $scope.clientData.funding.expectedProperty = response.property;
                    $scope.clientData.funding.expectedAusFixedInterest = response.ausFixedInterest;
                    $scope.clientData.funding.expectedIntFixedInterest = response.intFixedInterest;
                    $scope.clientData.funding.expectedCash = response.cash;
                    $scope.clientData.funding.expectedAttractiveAssets = response.attractiveAssets;

                    $scope.expectedGrowth = $scope.clientData.funding.expectedAusShares + $scope.clientData.funding.expectedIntShares + $scope.clientData.funding.expectedProperty
                            + $scope.clientData.funding.expectedAttractiveAssets;

                }
            );
    }

    $scope.clientFormSubmitted = false;
    
    $scope.updateClientFunding = function () {
        $scope.clientFormSubmitted = true;

        if ($scope.frmClientFunding.$valid) {
            clientAssetAllocationService.updateClientFunding($scope.clientData.funding)
            .then(
                    function (response) {
                        $state.go('app.clients.fundings', { "id": $stateParams.id });
                    }
                );
        }
    }

    $scope.updateTotal = function () {
        $scope.clientData.funding.totalAssets = parseFloat($scope.clientData.funding.personalAssets) + parseFloat($scope.clientData.funding.superAssets) +
            parseFloat($scope.clientData.funding.pensionAssets) + parseFloat($scope.clientData.funding.otherEntitiesAssets);

        $scope.actualTotalFund = $scope.clientData.funding.totalAssets;
    }

    $scope.updateTotalShares = function () {
        $scope.clientData.funding.totalShares = $scope.clientData.funding.actualAusShares + $scope.clientData.funding.actualIntShares + $scope.clientData.funding.actualProperty
                            + $scope.clientData.funding.actualAusFixedInterest + $scope.clientData.funding.actualIntFixedInterest + $scope.clientData.funding.actualCash
                            + $scope.clientData.funding.actualAttractiveAssets;

        $scope.actualGrowth = $scope.clientData.funding.actualAusShares + $scope.clientData.funding.actualIntShares + $scope.clientData.funding.actualProperty
                                + $scope.clientData.funding.actualAttractiveAssets;
    }

    $scope.dateOptions = {
        formatYear: 'yy',
        startingDay: 1
    };

    $scope.formats = ['dd/MM/yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
    $scope.format = $scope.formats[0];

    $scope.status = {
        opened: false,
        openedEntryDate: false,
        openedLifestyleObjectiveAt: false,
        openedNetWealthProjectionDate: false
    };

    $scope.$watch('clientData.funding.fundingPeriod', function () {
        if (!angular.isUndefined($scope.clientData.funding))
            $scope.clientData.funding.fundingPeriod = $filter('date')($scope.clientData.funding.fundingPeriod, $scope.format);
    });

    $scope.$watch('clientData.funding.entryDate', function () {
        if (!angular.isUndefined($scope.clientData.funding))
            $scope.clientData.funding.entryDate = $filter('date')($scope.clientData.funding.entryDate, $scope.format);
    });

    $scope.$watch('clientData.funding.lifestyleObjectiveAt', function () {
        if (!angular.isUndefined($scope.clientData.funding))
            $scope.clientData.funding.lifestyleObjectiveAt = $filter('date')($scope.clientData.funding.lifestyleObjectiveAt, $scope.format);
    });

    $scope.$watch('netWealthProjectionDate', function () {
        if (!angular.isUndefined($scope.clientData.funding)) {
            $scope.netWealthProjectionDate = $filter('date')($scope.netWealthProjectionDate, $scope.format);
        }
    });

    $scope.openLifestyleObjectiveAt = function ($event) {
        $scope.status.openedLifestyleObjectiveAt = true;
    };

    $scope.open = function ($event) {
        $scope.status.opened = true;
    };

    $scope.openEntryDate = function ($event) {
        $scope.status.openedEntryDate = true;
    };

    $scope.openNetWealthProjectionDate = function ($event) {
        $scope.status.openedNetWealthProjectionDate = true;
    };

    $scope.cancel = function () {
        $state.go('app.clients.fundings', { "id": $stateParams.id });
    }

    $scope.getClientFunding();

    $scope.importProjectedData = function () {
        $scope.importExcelFormSubmitted = false;
        $scope.rowNumYear = "";
        $scope.rowNumYear = "";
        $scope.netWealthProjectionDate = "";
        $scope.myFile = "";
        var fileCtrl = $('#fileCtrl');
        fileCtrl.val(null);
        //angular.element('fileCtrl').val(null);
        $scope.errorMessage = "";
        $('#aImportExcel').click();
    }

    $scope.uploadFile = function () {
        $scope.importExcelFormSubmitted = true;

        if ($scope.importExcelForm.$valid) {
            var file = $scope.myFile;
            if (file == '') {
                $scope.errorMessage = "Please select file to import.";
                return;
            }
            else {
                var fileParts = file.name.split('.')
                if (fileParts[fileParts.length - 1] != 'csv') {
                    $scope.errorMessage = "Upload *.csv files only";
                    return;
                }
            }


            var formData = {
                clientID : $stateParams.id,
                rowNumYear : $scope.rowNumYear,
                rowNumNetWealth : $scope.rowNumNetWealth,
                lastProjectionDate: $scope.netWealthProjectionDate,
                fundingId : $stateParams.fundingId
            }

            clientAssetAllocationService.uploadFileToUrl(formData, file).then(
                function (response) {
                    $scope.getProjections();
                    $scope.clientData.funding.lastProjectionDate = $scope.netWealthProjectionDate;
                });
        };
    }

    $scope.getProjections = function () {
        clientAssetAllocationService.getProjections($stateParams.id).then(
                function (response) {
                    $scope.projectedData = response;
                    $filter('filter')(response, { year: $scope.clientData.funding.fundingYear })[0];

                    $('#btnCancel').click();
                });
    }
}])