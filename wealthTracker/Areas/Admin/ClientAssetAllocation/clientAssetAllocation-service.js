angular.module('app')
.factory('clientAssetAllocationService', ['$q', '$http', function ($q, $http) {
    return {
        getClientFundingById: function (id, fundingId) {
            var defer = $q.defer();
            $http.get('api/clientFundings/' + id+"/"+fundingId)
            .success(function (response) {
                defer.resolve(response);
            });

            return defer.promise;
        },

        updateClientFunding: function (data) {
            var defer = $q.defer();
            $http.post("api/clientFundings", data)
            .success(function (response) {
                defer.resolve(response);
            });

            return defer.promise;
        },

        getClientFundings: function (data) {
            var defer = $q.defer();
            $http.post("api/clientFundings/getAll", data)
            .success(function (response) {
                defer.resolve(response);
            });

            return defer.promise;
        },
        deleteClientFundings: function (fundingId) {
            var defer = $q.defer();
                $http.get("api/clientFundings/deleteClientFunding/"+ fundingId)
            .success(function (response) {
                defer.resolve(response);
            });

            return defer.promise;
        },
        uploadFileToUrl: function (formData, file)
        {
            var defer = $q.defer();

            var fd = new FormData();
            fd.append('rowNumYear', formData.rowNumYear);
            fd.append('rowNumNetWealth', formData.rowNumNetWealth);
            fd.append('lastProjectionDate', formData.lastProjectionDate);
            fd.append('clientID', formData.clientID);
            fd.append('fundingId', formData.fundingId);
            fd.append('file', file);
            $http.post('api/clientFundings/uploadExcel', fd, {
                    headers: { 'Content-Type': undefined }
            })
                .success(function (response) {
                    defer.resolve(response);
                })
                .error(function () {
                });

            return defer.promise;
        },
        getProjections: function (clientId) {
            var defer = $q.defer();
            $http.get("api/clientFundings/getProjections/" + clientId)
            .success(function (response) {
                defer.resolve(response);
            });
            return defer.promise;
        },
    }

}])