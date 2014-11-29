
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
        .when('/user-edit/:userId',{
            templateUrl: 'app/view/userEdit.html',
            controller: 'UserEditController',
        })
        .when('/users',{
            templateUrl: 'app/view/users.html',
            controller: 'UsersController'
        })
        .otherwise({
            redirectTo:'/login'
        });
    }
    angular
        .module('app',['ngRoute', 'ui.bootstrap', 'ipCookie'])
        .config(config)
        .controller('LoginController',['$location','AuthService','$cacheFactory','$modal', LoginController])
        .controller('HomeController', ['$location','AuthService', 'PlacesService', 'CityService',HomeController])
        .controller('PlaceController', ['$location','AuthService','PlacesService','$routeParams',PlaceController])
        .controller('UserEditController',['$location','AuthService','$routeParams','UserService',UserEditController])
        .controller('UsersController',['$location', 'AuthService','UserService', UsersController])
        .factory('AuthService',['$http','ipCookie','$location','$rootScope','$q',AuthService])
        .factory('PlacesService',['$http','$q', PlacesService])
        .factory('CityService',['$http','$q', CityService])
        .factory('UserService', ['$http', '$q', UserService])
        .directive('navbar',['AuthService','$rootScope',navbarDirective]);