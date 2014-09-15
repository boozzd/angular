/**
 * Created by boozz on 02.09.14.
 */
    function config($routeProvider){
        $routeProvider.when('/login',{
            templateUrl: 'app/view/login.html',
            controller: 'LoginController'
        });
        $routeProvider.when('/home', {
            templateUrl: 'app/view/home.html',
            controller: 'HomeController'
        });
        $routeProvider.otherwise({
            redirectTo:'login'
        });
    }
    angular.module('app',['ngRoute', 'ui.bootstrap'])
        .config(config)
        .controller('LoginController',['$location','AuthService','$cacheFactory','$modal', LoginController])
        .controller('HomeController', ['$location','AuthService',HomeController])
        .factory('AuthService',['$http','$cacheFactory',AuthService]);