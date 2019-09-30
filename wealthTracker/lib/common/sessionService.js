'use strict';

angular.module('app')
    .factory('sessionService', ['$window', function ($window)
{
	return{
	    set: function (key, value) {
	        return $window.sessionStorage.setItem(key, value);
	    },

		get:function(key){
		    return $window.sessionStorage.getItem(key);
		},

		destroy: function (key) {
			//$http.post('data/destroy_session.php');
		    return $window.sessionStorage.removeItem(key);
		}
	};
}])