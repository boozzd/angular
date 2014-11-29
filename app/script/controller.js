function LoginController(location, auth, cacheFactory, modal){
    var contr = this;
    contr.credentials = {login: "", password: ""};
    contr.alerts = [];
    contr.login = function(){
        auth.auth(this.credentials).then(function(value) {
            location.path('/home');
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

function HomeController(location, auth, placesService, cityService){
    var contr = this;
    contr.showNextButton = true;
    if(auth.isAuth()){
        contr.credentials = auth.getAuth();
        contr.page = 1;
        contr.selectedtown = [];
        placesService.getPlaces(contr.page).then(function(data){
            contr.places = data.places;
        });
        contr.nextPlace = function(){
            contr.page++;
            var ids = [];
            angular.forEach(contr.selectedtown, function(key , value){
                if(key){
                    ids.push(value);
                }
            });
            var place = placesService.getPlaces(contr.page, ids).then(function(data){
                angular.forEach(data.places,function(value){
                        contr.places.push(value);
                });
                if(data.count < 5){
                    contr.showNextButton = false;
                                    }
            });
        }
        cityService.getCity().then(function(result){
            if(angular.isObject(result.data)){
                contr.city = result.data;
            }
        });
        contr.city = {
            'c_id': 1,
            'c_name': 'Украина'
        }
        contr.getTown = function(){
            contr.page = 1;
            cityService.getTown(contr.selectcity).then(function(response){
                if(angular.isObject(response.data)){
                    contr.towns = response.data;
                }else{
                    contr.towns  = [];
                    placesService.getPlaces(contr.page).then(function(data){
                        contr.places = data.places;
                    });
                }
            });
        }
        contr.changeTown = function(){
            contr.page = 1;
            var ids = [];
            angular.forEach(contr.selectedtown, function(key , value){
                if(key){
                    ids.push(value);
                }
            });
            placesService.getPlaces(contr.page, ids).then(function(data){
                contr.places = data.places;
            });
        }

    }else{
        location.path('/login');
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
        location.path('home');
    }
}

function UserEditController($location,auth, $routeParams, userService){
    var contr = this;
    var placeAdd = {};
    var userId = $routeParams.userId;
    contr.alerts = [];
    if(auth.isAuth() && userId){
        if(userId){
            userService.getUserData(userId).then(function(data){
                contr.user = data.data[0];
            });
            contr.send = function(user){
                contr.user = angular.copy(user);
                contr.formError = (contr.user.password == contr.user.passwordConfirm) ? false : true;
                if(!contr.formError){
                    userService.changeUser(contr.user).then(function(result){
                        contr.addAlert({'type': 'success', 'msg': 'Данные изменены.'});
                    }, function(result){
                        contr.addAlert({'type': 'danger', 'msg': 'Произошла ошибка.'});
                    });
                }
            }
            contr.reset = function(){
                contr.user = {};
            }
            contr.addAlert = function(data){
                contr.alerts.push(data);
            }
            contr.removeAlerts = function(index){
                contr.alerts.splice(index, 1);
            }
        }else{
            $location.path('/users');
        }
    }else{
        $location.path('/home');
    }
}

function UsersController(location, auth, userService){
    var contr = this;
    contr.alerts = [];
    if(auth.isAuth()){
        userService.getUsersData().then(function(data){
            contr.users = data.data;
        });
        contr.removeUser = function(userId){
            userService.removeUser(userId).then(function(response){
                for(var k in contr.users){
                    if(userId == contr.users[k].id){
                        delete contr.users[k];
                    }
                }
                contr.addAlert({'type': 'success', 'msg': 'Пользователь удален.'});
            },function(response){
                contr.addAlert({'type': 'danger', 'msg': 'Произошла ошибка. У Вас нет прав для удаления пользователя.'});
            });
        }
        contr.addAlert = function(data){
            contr.alerts.push(data);
        }
        contr.removeAlerts = function(index){
            contr.alerts.splice(index, 1);
        }
    }else{
        location.path('/home');
    }
}