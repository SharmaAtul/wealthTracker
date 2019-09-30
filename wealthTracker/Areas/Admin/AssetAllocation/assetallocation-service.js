angular.module('app')
.factory('assetAllocationService', ['$q', '$http', function ($q, $http) {
    return {
        getAssetAllocation: function () {
            var defer = $q.defer();
            $http.get('api/assetAllocationMasters')
            .success(function (data) {
                defer.resolve(data);
            });

            return defer.promise;
        },
        getAssetAllocationByKey: function (id) {
            var defer = $q.defer();
            $http.get('api/assetAllocationMasters/'+id)
            .success(function (data) {
                defer.resolve(data);
            });

            return defer.promise;
        },
        updateAssetAllocation: function (assetAllocationMasterData) {
            var defer = $q.defer();
            $http.post("api/assetAllocationMasters", assetAllocationMasterData)
            .success(function (data) {
                defer.resolve(data);
            });

            return defer.promise;
        },
        updateRiskProfileTitle: function (data) {
            var defer = $q.defer();
            $http.post("api/assetAllocationMasters/updateTitle", data)
            .success(function (data) {
                defer.resolve(data);
            });

            return defer.promise;
        }

        
    }

}])