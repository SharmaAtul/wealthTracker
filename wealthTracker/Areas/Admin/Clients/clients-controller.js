'use strict'

angular.module("app")
    .controller('clientsController', ['$scope', '$state', 'clientService', '$filter', 'ngToast', 'loginService', function ($scope, $state, clientService, $filter, ngToast, loginService)
    {

        var user = loginService.getLoggedInUser();
        $scope.isAdmin = user.user_role == 'admin';

        $scope.totalItems = 0;
        $scope.currentPage = 1;
        $scope.pageSize = 20;

        $scope.clients = [];
        var closeUserPopup = false;
        $scope.getClients = function () {
            $scope.totalItems = 0;
            $scope.currentPage = 1;
            $scope.pageSize = 20;

            clientService.getClients($scope.selectedClient.id, $scope.currentPage, $scope.pageSize)
                .then(
                        function (data)
                        {
                            if (data.records != null) {
                                $scope.clients = data.records.sort(function (a, b) {
                                    return a.lastName - b.lastName;
                                });
                            } else {
                                $scope.clients = [];
                            }

                            $scope.totalItems = data.totalCount;

                            if (closeUserPopup==true)
                                $('#closeUserModal').click();
                        }
                    );
        }

        
        $scope.selectedClient = {};
        $scope.appClients = [];

        $scope.getAppClients = function () {
            clientService.getAppClients(1,0)
                .then(
                        function (data) {
                            var defaultClientIndex = -1;
                            for (var i = 0; i <= data.records.length - 1; i++) {
                                var rec = data.records[i];

                                if (rec.id == data.defaultClient)
                                    defaultClientIndex = i;

                                $scope.appClients.push(data.records[i]);
                            }

                            if (defaultClientIndex != -1) {
                                $scope.selectedClient = $scope.appClients[defaultClientIndex];
                                $scope.getClients();
                            }
                        }
                    );
        }

        $scope.getAppClients();

        $scope.setPage = function (pageNo) {
            $scope.currentPage = pageNo;
        };

        $scope.pageChanged = function () {
            $scope.getClients();
            //$log.log('Page changed to: ' + $scope.currentPage);
        };

        $scope.pagesToDisplay = 5; //number of pages to display in the pagination

        $scope.isClientEdit = false;
        $scope.clientHeaderTitle = '';

        $scope.user = {};

        $scope.clientFormSubmitted = false;

        $scope.addNewClient = function ()
        {
            $scope.clientFormSubmitted = false;

            $scope.user = {
                userEmail : '',
                userPassword : '',
                isActive : true,
                firstName : '',
                lastName : '',
                dob: '',
                spouseFirstName : '',
                spouseLastName : '',
                spouseDOB: '',
                userType : 'client'
            };
            
            $scope.clientHeaderTitle = 'Add new client';
            $scope.isClientEdit = false;
            $('#aClientForm').click();
        }

        $scope.editClient = function (index)
        {
            $scope.clientFormSubmitted = false;

            $scope.user = $scope.clients[index];
            $scope.clientHeaderTitle = 'Edit client details';
            $scope.isClientEdit = true;
            $('#aClientForm').click();
        }
        
        $scope.registerUser = function ()
        {
            $scope.clientFormSubmitted = true;

            if ($scope.clientForm.$valid) {
                clientService.registerClient($scope.user)
                .then(
                        function (data) {
                            closeUserPopup = true;
                            var message = "Client added successfully!";

                            if ($scope.isClientEdit) {
                                message = "Client updated successfully!";
                            }

                            ngToast.success({
                                content: message
                            });

                            $scope.getClients();
                        }
                    );
            }
        }

        $scope.dateOptions = {
            formatYear: 'yy',
            startingDay: 1
        };

        $scope.formats = ['dd/MM/yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
        $scope.format = $scope.formats[0];

        $scope.status = {
            opened: false,
            spouseDOBopened: false
        };

        $scope.$watch('user.dob', function () {
            $scope.user.dob = $filter('date')($scope.user.dob, $scope.format);
        });

        $scope.$watch('user.spouseDOB', function () {
            $scope.user.spouseDOB = $filter('date')($scope.user.spouseDOB, $scope.format);
        });

        $scope.open = function ($event) {
            $scope.status.opened = true;
        };

        $scope.spouseDOBopen = function ($event) {
            $scope.status.spouseDOBopened = true;
        };

        $scope.showAssetAllocation = function (index) {
            var userId = $scope.clients[index].userID;
            $state.go('app.clients.fundings', { "id": userId });
        }
        
        $scope.showReport = function (index) {
            var userId = $scope.clients[index].userID;
            $state.go('app.dashboard', { "id": userId });
        }
    }])
