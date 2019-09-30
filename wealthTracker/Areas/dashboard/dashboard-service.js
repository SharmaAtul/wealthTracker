angular.module('app').factory('dashboardService', ['$q', '$http', function ($q, $http) {

    return {
        getDashboardData: function (id) {
            var defer = $q.defer();
            $http.get('api/clientFundings/getDashboardData/' + id)
            .success(function (data) {
                defer.resolve(data);
            });

            return defer.promise;
        }
    }

}])