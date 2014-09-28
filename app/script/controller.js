function LoginController(location, auth, cacheFactory, modal){
    var contr = this;
    contr.credentials = {login: "", password: ""};
    contr.alerts = [];
    contr.login = function(){
        auth.auth(this.credentials).then(function(value) {
            location.path('home');
        },function(){
            contr.errorMsg = 'Комбинация логина и пароля не совпадает!';
            contr.credentials = {login: "", password: ""};
        });
    };

    contr.registrationOpen = function(){
            var modalInstance = modal.open({
                templateUrl: 'registrationTemplate.html',
                controller: regModalController,
                resolve: {}
            }); 
            modalInstance.result.then(function(data){
                auth.register(data).then(function(value){
                    contr.addAlert({'type': 'success', 'msg': 'Вы успешно зарегистрировались.'});
                },function(){
                    contr.addAlert({'type': 'danger', 'msg': 'Произошла ошибка. Повторите регистрацию.'});
                });
            });
    }

    contr.addAlert = function(data){
        contr.alerts.push(data);
    }
    contr.removeAlerts = function(index){
        contr.alerts.splice(index, 1);
    }

}
function regModalController($scope, $modalInstance){
    $scope.credentials = {login: "", password: "", confirmPass: "", email: ""};
    $scope.regForm = null;
    $scope.registration = function(){
        var validPass = ($scope.credentials.password === $scope.credentials.confirmPass) ? true : false;
        if($scope.regForm.$valid && validPass){
            $modalInstance.close($scope.credentials);
        }else{
            $scope.errorMsg = "Поля заполнены не верно!";
            $scope.credentials.password = "";
            $scope.credentials.confirmPass = "";
        }
    };
    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    };

    $scope.setForm = function(form){
        $scope.regForm = form;
    }
}

function HomeController(location, auth, placesService, rootScope){
    var contr = this;
    if(auth.isAuth()){
        contr.credentials = auth.getAuth();

        contr.logout = function(){
            auth.logout();
        };

        placesService.getPlaces().then(function(data){
            contr.places = data;
        });

    }else{
        location.path('login');
    }
}

function PlaceController(location, auth,placesService,$routeParams){
    var contr = this;
    if(auth.isAuth()){
        var url = $routeParams.placeUrl;
        placesService.getPlace(url).then(function(data){
            contr.place = data;
        });

    }else{
        location.path('login');
    }
}