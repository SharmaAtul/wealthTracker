angular.module('app')
.factory('clientService', ['$q','$http', function ( $q, $http) {
    return {
        getClients: function (selectedClientId, currentPage, pageSize) {
            var defer = $q.defer();
            $http.get('api/users/getAll/' + selectedClientId + '/' +(currentPage - 1) + '/' + pageSize)
            .success(function (data) {
                defer.resolve(data);
            });

            return defer.promise;
        },
        getAppClients: function (currentPage,pageSize) {
            var defer = $q.defer();
            $http.get('api/appClients/getAppClientList/' + (currentPage-1) + '/' + pageSize)
            .success(function (data) {
                defer.resolve(data);
            });

            return defer.promise;
        },
        registerClient: function (user) {
            var defer = $q.defer();
            $http.post("api/users/register", user)
            .success(function (data) {
                defer.resolve(data);
            });

            return defer.promise;
        }
    }
}])