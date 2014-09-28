function navbarDirective(auth, $rootScope){
    var elements = '<div class="navbar navbar-inverse" role="navigation" ng-show="menuShow">'+
        '<div class="container-fluid">'+
        '<div class="navbar-header">'+
        '<a class="navbar-brand">{{credentials["u_name"]}}</a>'+
        '</div>'+
        '<div class="collapse navbar-collapse">'+
        '<ul class="nav navbar-nav navbar-right">'+
        '<li><a href="#" ng-click="logout()">Выход</a></li>'+
        '</ul>'+
        '</div>'+
        '</div>'+
        '</div>';
    return {
        restrict: 'EA',
        template: elements,
        link: function(scope, element, attrs){
            $rootScope.$on('login', function(event,data){
                scope.menuShow = !angular.isUndefined(data);
                scope.credentials = data;
            });
            scope.logout = function(){
                auth.logout();
            }
        }
    }
}