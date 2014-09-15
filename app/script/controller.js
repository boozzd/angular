function LoginController(location, auth, cacheFactory, modal){
    var contr = this;
    contr.credentials = {login: "", password: ""};
    contr.alerts = [];
    contr.login = function(){
        function redirector(value){
            if(angular.isObject(value)){
                location.path('home');
            }else{
                contr.errorMsg = 'Комбинация логина и пароля не совпадает!';
                contr.credentials = {login: "", password: ""};
            }
        }
        auth.auth(this.credentials, redirector);
    };
    contr.registrationOpen = function(){
            var modalInstance = modal.open({
                templateUrl: 'registrationTemplate.html',
                controller: regModalController,
                resolve: {}
            }); 
            modalInstance.result.then(function(data){
                auth.register(data, showAlert);
            });
            function showAlert(data){
                if(data == 1){
                    contr.addAlert({'type': 'success', 'msg': 'Вы успешно зарегистрировались.'});
                }else{
                    contr.addAlert({'type': 'danger', 'msg': 'Произошла ошибка. Повторите регистрацию.'});
                }
            }   
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

function HomeController(location, auth){
    var contr = this;
    if(auth.isAuth()){
        contr.credentials = auth.getAuth();
        contr.logout = function(){
            auth.logout(redirector);
            function redirector(){
                location.path('login');
            }
        }
    }else{
        location.path('login');
    }
}