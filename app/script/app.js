/**
 * Created by boozz on 02.09.14.
 */
    function config($routeProvider){
        $routeProvider.when('/login',{
            templateUrl: 'app/view/login.html',
            controller: 'LoginController'
        })
        .when('/home', {
            templateUrl: 'app/view/home.html',
            controller: 'HomeController'
        })
        .when('/home/:placeUrl',{
            templateUrl: 'app/view/place.html',
            controller: 'PlaceController'
            })
        .otherwise({
            redirectTo:'login'
        });
    }
    angular
        .module('app',['ngRoute', 'ui.bootstrap', 'ipCookie'])
        .config(config)
        .controller('LoginController',['$location','AuthService','$cacheFactory','$modal', LoginController])
        .controller('HomeController', ['$location','AuthService', 'PlacesService', 'CityService',HomeController])
        .controller('PlaceController', ['$location','AuthService','PlacesService','$routeParams',PlaceController])
        .factory('AuthService',['$http','ipCookie','$location','$rootScope','$q',AuthService])
        .factory('PlacesService',['$http','$q', PlacesService])
        .factory('CityService',['$http','$q', CityService])
        .directive('navbar',['AuthService','$rootScope',navbarDirective]);